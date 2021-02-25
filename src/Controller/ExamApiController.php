<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\Exam\AnswerExam;
use App\Message\Exam\CreateExam;
use App\Message\Exam\ShowExam;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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

    /**
     * @Route("/exam/{examId}/student/{studentId}", name="answer_exam", methods={"POST"})
     * @param string $examId
     * @param string $studentId
     *
     * @return Response
     */
    public function answerExam(Request $request, string $examId, string $studentId): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $answers = $data['answers'];
        if (!\is_array($answers)) {
            throw new BadRequestHttpException('missing answers');
        }

        $answerExam = new AnswerExam((int) $examId, (int) $studentId, $answers);
        $examResult = $this->handle($answerExam);
        if (null === $examResult) {
            return new Response('No exam in progress', Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $this->getJsonSerializer()->serialize($examResult, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
