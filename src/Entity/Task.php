<?php


namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id()
     * указание что поле является индентификатором
     * @ORM\GeneratedValue()
     * данное поле будет в базе иметь автоинкремент
     * @ORM\Column(type="integer")
     * описание столбца в базе  (тип = целое число)
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * колона типа string длинной 255 символов
     */
    protected $task;
    /**
     * @ORM\Column(type="datetime")
     * дата время
     */
    protected $dueDate;

    public function getId(): ?int                 // alt+insert - getters - создает метод для получения property
    {
        return $this->id;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }
    public function setTask(string $task): void
    {
        $this->task = $task;
    }
    public function dueDate(): ?\DateTime
    {
        return $this->dueDate;
    }
    public function setDueDate(?\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }
}