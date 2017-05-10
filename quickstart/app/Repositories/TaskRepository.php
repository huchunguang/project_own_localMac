<?php 
namespace App\Repositories;
use App\User;
use App\Task;
use Illuminate\Database\Eloquent\Collection;
class TaskRepository
{
    /**
     * get All for give the user
     * @param User $user
     * @return Collection
     */
    public  function forUser(User $user)
    {
        return Task::where('user_id',$user->id)->orderBy('created_at','asc')->get();
    }
}