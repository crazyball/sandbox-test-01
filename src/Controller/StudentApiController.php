<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\Student\CreateStudent;
use App\Message\Student\DeleteStudent;
use App\Message\Student\DisplayStudent;
use App\Message\Student\UpdateStudent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StudentApiController
{
    use HandleTrait;

    private SerializerInterface $serializer;

    public function __construct(MessageBusInterface $messageBus, SerializerInterface $serializer)
    {
        $this->messageBus = $messageBus;
        $this->serializer = $serializer;
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

        if (empty($firstName) || empty($lastName)) {
            throw new NotFoundHttpException('Expecting mandatory parameters.');
        }

        $createStudentMessage = new CreateStudent($firstName, $lastName);
        $this->handle($createStudentMessage);

        return new JsonResponse(['status' => 'Student created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/students", name="delete_student", methods={"DELETE"})
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \JsonException
     */
    public function remove(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $id = $data['id'];

        if (empty($id)) {
            throw new NotFoundHttpException('Expecting mandatory parameters.');
        }

        $deleteStudentMessage = new DeleteStudent($id);
        $this->handle($deleteStudentMessage);

        return new JsonResponse(['status' => 'Student deleted'], Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/students", name="get_student", methods={"GET"})
     * @param Request $request
     *
     * @return Response
     * @throws \JsonException
     */
    public function get(Request $request): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $id = $data['id'];

        if (empty($id)) {
            throw new NotFoundHttpException('Expecting mandatory parameters.');
        }

        $displayStudentMessage = new DisplayStudent($id);
        $student = $this->handle($displayStudentMessage);

        if (null === $student) {
            throw new NotFoundHttpException('Student not found.');
        }

        return new Response($this->serializer->serialize($student, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'ignored_attributes' => ['classroom']
        ]), Response::HTTP_OK);
    }

    /**
     * @Route("/students", name="update_student", methods={"PUT"})
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \JsonException
     */
    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $id = $data['id'];
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        if (empty($firstName) || empty($lastName)) {
            throw new NotFoundHttpException('Expecting mandatory parameters.');
        }

        $createStudentMessage = new UpdateStudent($id, $firstName, $lastName);
        $this->handle($createStudentMessage);

        return new JsonResponse(['status' => 'Student updated'], Response::HTTP_ACCEPTED);
    }
}
