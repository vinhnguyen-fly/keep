<?php  namespace Keep\Http\Composers; 

use Illuminate\Contracts\View\View;
use Keep\Repositories\Task\TaskRepositoryInterface;
use Keep\Repositories\User\UserRepositoryInterface;

class AdminPanelComposer {

    protected $userRepository;
    protected $taskRepository;

    public function __construct(UserRepositoryInterface $userRepository,
                                TaskRepositoryInterface $taskRepository)
    {
        $this->userRepository = $userRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * Compose admin panel views.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('users', $this->userRepository->paginate(25));
        $view->with('userCount', $this->userRepository->all()->count());
        $view->with('tasks', $this->taskRepository->paginate(40));
        $view->with('taskCount', $this->taskRepository->all()->count());
    }
}