<?php

namespace App\Repositories\Contracts;

use App\Models\Todos;
use App\Models\User;

/**
 * Interface TodosRepository.
 */
interface TodosRepository  extends BaseRepository
{
    /**
     * @param array $input
     * @return void
     */
    public function store(array $input);

    /**
     * @param array $input
     * @return void
     */
    public function update(array $input);

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function destroy(User $user, Todos $todo);

    /**
     * @param array $ids
     *
     * @return mixed
     */
    public function batchDestroy(array $ids);

    /**
     * @param array $ids
     *
     * @return mixed
     */
    public function batchEnable(array $ids);

    /**
     * @param array $ids
     *
     * @return mixed
     */
    public function batchDisable(array $ids);
}
