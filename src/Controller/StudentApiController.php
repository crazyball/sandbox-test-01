<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Exam;
use App\Message\Student\CreateStudent;
use App\Message\Student\DeleteStudent;
use App\Message\Student\DisplayStudent;
use App\Message\Student\ListStudents;
use App\Message\Student\ShowStudentExam;
use App\Message\Student\UpdateStudent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class StudentApiController extends AbstractController
{
    use HandleTrait;

    private SerializerInterface $serializer;

    private ValidatorInterface $validator;

    public function __construct(
        MessageBusInterface $messageBus,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->messageBus = $messageBus;
        $this->serializer = $serializer;
        $this->validator  = $validator;
    }

    /**
     * @Route("/students", name="add_student", methods={"POST"})
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \JsonException
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $firstName = $data['firstName'];
        $lastName  = $data['lastName'];
        $email = $data['email'];

        $createStudentMessage = new CreateStudent($firstName, $lastName, $email);

        $errors = $this->validator->validate($createStudentMessage);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->handle($createStudentMessage);

        return new JsonResponse(['status' => 'Student created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/students/{id}", name="delete_student", methods={"DELETE"})
     * @param string $id
     *
     * @return JsonResponse
     */
    public function remove(string $id): JsonResponse
    {
        $deleteStudentMessage = new DeleteStudent((int) $id);
        $this->handle($deleteStudentMessage);

        return new JsonResponse(['status' => 'Student deleted'], Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/students", name="list_students", methods={"GET"})
     *
     * @return Response
     */
    public function list(): Response
    {
        $listStudents = new ListStudents();
        $students = $this->handle($listStudents);

        $jsonContent = $this->getJsonSerializer()->serialize($students, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/students/{id}", name="get_student", methods={"GET"})
     *
     * @param string $id
     *
     * @return Response
     */
    public function get(string $id): Response
    {
        $displayStudentMessage = new DisplayStudent((int) $id);
        $student = $this->handle($displayStudentMessage);

        if (null === $student) {
            throw new NotFoundHttpException('Student not found.');
        }

        $jsonContent = $this->getJsonSerializer()->serialize($student, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/students/{id}", name="update_student", methods={"PUT"})
     * @param Request $request
     * @param string  $id
     *
     * @return JsonResponse
     * @throws \JsonException
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        if (empty($firstName) || empty($lastName)) {
            throw new NotFoundHttpException('Expecting mandatory parameters.');
        }

        $createStudentMessage = new UpdateStudent((int) $id, $firstName, $lastName);
        $this->handle($createStudentMessage);

        return new JsonResponse(['status' => 'Student updated'], Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/students/{id}/exam", name="find_student_exam", methods={"GET"})
     * @param string $id
     *
     * @return Response
     */
    public function findExamForStudent(string $id): Response
    {
        $showStudentExam = new ShowStudentExam((int) $id);
        $exam = $this->handle($showStudentExam);
        if (null === $exam) {
            return new Response('No exam in progress', Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $this->getJsonSerializer()->serialize($exam, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
