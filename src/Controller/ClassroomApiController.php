<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\Classroom\CreateClassroom;
use App\Message\Classroom\DeleteClassroom;
use App\Message\Classroom\DisplayClassroom;
use App\Message\Classroom\ListClassrooms;
use App\Message\Classroom\UpdateClassroom;
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
class ClassroomApiController extends AbstractController
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
     * @Route("/classrooms", name="add_classroom", methods={"POST"})
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \JsonException
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $name = $data['name'];

        $createClassroomMessage = new CreateClassroom($name);

        $errors = $this->validator->validate($createClassroomMessage);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->handle($createClassroomMessage);

        return new JsonResponse(['status' => 'Classroom created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/classrooms/{id}", name="delete_classroom", methods={"DELETE"})
     * @param string $id
     *
     * @return JsonResponse
     */
    public function remove(string $id): JsonResponse
    {
        $deleteClassroomMessage = new DeleteClassroom((int) $id);
        $this->handle($deleteClassroomMessage);

        return new JsonResponse(['status' => 'Classroom deleted'], Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/classrooms", name="list_classrooms", methods={"GET"})
     *
     * @return Response
     */
    public function list(): Response
    {
        $listClassrooms = new ListClassrooms();
        $classrooms = $this->handle($listClassrooms);

        return new Response($this->serializer->serialize($classrooms, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'ignored_attributes' => ['students']
        ]), Response::HTTP_OK);
    }

    /**
     * @Route("/classrooms/{id}", name="get_classroom", methods={"GET"})
     * @param string $id
     *
     * @return Response
     */
    public function get(string $id): Response
    {
        $displayClassroomMessage = new DisplayClassroom((int) $id);
        $classroom = $this->handle($displayClassroomMessage);

        if (null === $classroom) {
            throw new NotFoundHttpException('Classroom not found.');
        }

        $jsonContent = $this->getJsonSerializer()->serialize($classroom, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/classrooms/{id}", name="update_classroom", methods={"PUT"})
     * @param Request $request
     * @param string  $id
     *
     * @return JsonResponse
     * @throws \JsonException
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $name = $data['name'];
        if (empty($name)) {
            throw new NotFoundHttpException('Expecting mandatory parameters.');
        }

        $createClassroomMessage = new UpdateClassroom((int) $id, $name);
        $this->handle($createClassroomMessage);

        return new JsonResponse(['status' => 'Classroom updated'], Response::HTTP_ACCEPTED);
    }
}
