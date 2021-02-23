<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\Classroom\CreateClassroom;
use App\Message\Classroom\DeleteClassroom;
use App\Message\Classroom\DisplayClassroom;
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

class ClassroomApiController
{
    use HandleTrait;

    private SerializerInterface $serializer;

    private ValidatorInterface $validator;

    public function __construct(
        MessageBusInterface $messageBus,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    )
    {
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
     * @Route("/classrooms", name="delete_classroom", methods={"DELETE"})
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

        $deleteClassroomMessage = new DeleteClassroom($id);
        $this->handle($deleteClassroomMessage);

        return new JsonResponse(['status' => 'Classroom deleted'], Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/classrooms", name="get_classroom", methods={"GET"})
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

        $displayClassroomMessage = new DisplayClassroom($id);
        $classroom = $this->handle($displayClassroomMessage);

        if (null === $classroom) {
            throw new NotFoundHttpException('Classroom not found.');
        }

        return new Response($this->serializer->serialize($classroom, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]), Response::HTTP_OK);
    }

    /**
     * @Route("/classrooms", name="update_classroom", methods={"PUT"})
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \JsonException
     */
    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $id = $data['id'];
        $name = $data['name'];
        if (empty($name)) {
            throw new NotFoundHttpException('Expecting mandatory parameters.');
        }

        $createClassroomMessage = new UpdateClassroom($id, $name);
        $this->handle($createClassroomMessage);

        return new JsonResponse(['status' => 'Classroom updated'], Response::HTTP_ACCEPTED);
    }
}
