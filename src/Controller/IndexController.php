<?php


namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\TaskChoice;
use App\Form\Type\TaskName;
use App\Form\Type\TaskRandomType;
use App\Form\Type\TaskSearchType;
use App\Form\Type\TaskSelect;
use App\Form\Type\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController // index наследуется от abstract
{
    /**
     * @Route("/", name="index")
     * $form->handleRequest($request); - обработать запрос формой
     * $form->isSubmitted() - проверка отправлена ли форма
     * $form->isValid() - проверяет валидна ли форма (загуглить слово валид)
     * $form->getData() - возвращает данные из формы
     * $entityManager->persist(Entity) - добавить сущность для отслеживания в менеджер
     * $entityManager->flush() - сохранить текущее состояние всех сущностей в менеджере
     * $repository->findOneById(1); - найти одно значение в репозитории
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Task::class);
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('thanks', [
                'id' => $task->getId()
            ]);
        }

        return $this->render('test/test.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/thank-you", name="thanks")
     */
    public function ThankYouAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->get('id');
        $repository = $entityManager->getRepository(Task::class);
        $task = $repository->findOneById($id);

        return $this->render('test/ThankYou.html.twig', [
            'task' => $task
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/search", name="search")
     */
    public function SearchTaskAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskSearchType::class); // результат метода createForm необходимо положить в переменную $form, $form = $this->createForm('\App\Form\Type\TaskSearchType')
        $form->handleRequest($request); // нужно обработать запрос формой (не факт что она отправлена)
        if ($form->isSubmitted() && $form->isValid()) { // проверяет валидность формы
            $id = $form->get('id')->getData(); // берет айди
            if ($id == null) { //проверяет айди на пустое значение
                return $this->render('test/search.html.twig', [
                    'form' => $form->createView(),
                    'task' => null
                ]);
            }
            $repository = $entityManager->getRepository(Task::class);
            $task = $repository->findOneById($id);
            return $this->render('test/search.html.twig', [
                'form' => $form->createView(),
                'task' => $task
            ]);
        }
        return $this->render('test/search.html.twig', [
            'form' => $form->createView(),
            'task' => null
        ]);
    }

    /**
     * @Route("/random", name="random")
     */
    public function RandomAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = null;
        $form = $this->createForm(TaskRandomType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $id = random_int(0, 3);
            $repository = $entityManager->getRepository(Task::class);
            $task = $repository->findOneById($id);
        }
        return $this->render('test/RandomTask.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/name", name="name")
     * name используется в твиге чтобы получить по нему ссылку (обычно одинаковые)
     */
    public function TaskName(Request $request, EntityManagerInterface $entityManager): Response
    {
        $result = false;
        $form = $this->createForm(TaskName::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Name = $form->get('Name')->getData();
            $Surname = $form->get('Surname')->getData();
            if ($Name === $Surname) {
                $result = true;
            }
        }
        return $this->render('test/name.html.twig', [
            'form' => $form->createView(),
            'result' => $result
        ]);

    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/taskSelect", name="taskSelect")
     */
    public function TaskSelect(Request $request): Response
    {
        $result = 'Нихера';
        $form = $this->createForm(TaskSelect::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dogOrCat = $form->get('Text')->getData();
            if ($dogOrCat === 'кот') {
                $result = 'мяу';
            } elseif ($dogOrCat === 'собака') {
                $result = 'гау';
            }
        }
        return $this->render('test/taskselect.html.twig', [
            'form' => $form->createView(),
            'result' => $result
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/choice", name="choice")
     */
    public function YourChoice(Request $request): Response
    {
        $result = '1 - Делать хуйню. 2 - Делать полезные вещи';
        $form = $this->createForm(TaskChoice::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->IsValid()) {
            $Answer = $form->get('Choice')->getData();
        }
        if ($Answer == 1) {
            $result = 'Ну и дурак';
        } elseif ($Answer == 2) {
            $result = 'молодец';
        } else {
            $result = 'Выбор только из 1 и 2, где 1 - Делать хуйню. 2 - Делать полезные вещи';
        }
        return $this->render('test/choice.html.twig', [
            'form' => $form->createView(),
            'result' => $result
        ]);
    }
    public function Dice(request $request): Response
    {
        $form1 = $this->createForm(TaskDicing::class);
        $form1->handleRequest($request);
        if($form1->isSubmitted())
        {
            $dice1 = random_int(1, 6);
        }
        $form2 = $this->createForm();
        $form2->handleRequest($request);
        if($form2->isSubmited())
        {
            $dice2 = random_int(1, 6);
        }
        return $this->render('test/dicing.html.twig'),
        [
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),

        ]);
    }
}