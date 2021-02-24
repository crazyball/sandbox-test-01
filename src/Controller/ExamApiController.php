<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\Exam\CreateExam;
use App\Message\Exam\ShowExam;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ExamApiController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/exam", name="create_exam", methods={"POST"})
     *
     * Generate a new Exam for a given classroom
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \JsonException
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $createExam = new CreateExam((int) $data['classroom']);

        $exam = $this->handle($createExam);

        return new JsonResponse($exam, Response::HTTP_CREATED);
    }

    /**
     * @Route("/exam/{id}", name="show_exam", methods={"GET"})
     *
     * Show current exam with state and stats
     *
     * @param string $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(string $id): Response
    {
        $showExam = new ShowExam((int) $id);

        $exam = $this->handle($showExam);

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
