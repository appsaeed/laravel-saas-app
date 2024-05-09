<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\GeneralException;
use App\Models\Customer;
use App\Models\RoleUser;
use App\Models\Todos;
use App\Models\User;
use App\Repositories\Contracts\TodosRepository;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class EloquentTodosRepository extends EloquentBaseRepository implements TodosRepository {

    /**
     * @param  array  $input
     * @param  bool  $confirmed
     *
     * @return User
     * @throws GeneralException
     * @throws Exception
     *
     */
    public function store( array $input ) {
        Todos::create( [
            'name' => $input['name'],
            'title' => $input['title'],
            'description' => isset( $input['description'] ) ?? $input['description'],
            'assign_to' => $input['assign_to'],
            'working_users' => isset( $input['working_users'] ) ?? $input['working_users'],
            'update_message' => isset( $input['update_message'] ) ?? $input['update_message'],
            'status' => $input['status'],
            'deadline' => isset( $input['edline'] ) ?? $input['edline'],
            'note' => isset( $input['note'] ) ?? $input['note'],
        ] );
    }

    /**
     * @param  User  $user
     * @param  array  $input
     *
     * @return User
     * @throws Exception|Throwable
     *
     * @throws Exception
     */
    public function update( array $input ) {
    }

    /**
     * @param  User  $user
     *
     * @return void
     * @throws Exception|Throwable
     *
     */
    public function destroy( User $user, Todos $task ) {
    }

    /**
     * @param  array  $ids
     *
     * @return mixed
     * @throws Exception|Throwable
     *
     */
    public function batchDestroy( array $ids ): bool {
        DB::transaction( function () use ( $ids ) {
            // This wont call eloquent events, change to destroy if needed
            foreach ( $this->query()->whereIn( 'uid', $ids )->cursor() as $administrator ) {
                RoleUser::where( 'user_id', $administrator->id )->delete();
                Customer::where( 'user_id', $administrator->id )->delete();
                $administrator->delete();
            }
        } );

        return true;
    }

    /**
     * @param  array  $ids
     *
     * @return mixed
     * @throws Exception|Throwable
     *
     */
    public function batchEnable( array $ids ): bool {
        DB::transaction( function () use ( $ids ) {
            if ( $this->query()->whereIn( 'uid', $ids )
                ->update( ['status' => true] )
            ) {
                return true;
            }

            throw new GeneralException( __( 'locale.exceptions.something_went_wrong' ) );
        } );

        return true;
    }

    /**
     * @param  array  $ids
     *
     * @return mixed
     * @throws Exception|Throwable
     *
     */
    public function batchDisable( array $ids ): bool {
        DB::transaction( function () use ( $ids ) {
            if ( $this->query()->whereIn( 'uid', $ids )
                ->update( ['status' => false] )
            ) {
                return true;
            }

            throw new GeneralException( __( 'locale.exceptions.something_went_wrong' ) );
        } );

        return true;
    }
}
