<?php 

namespace App\Domains\Travel\Policies;
use App\Domains\Travel\Models\User;
use App\Domains\Travel\Models\TravelRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelRequestPolicies
{
    use HandlesAuthorization;

    public static function canViewAny(User $user): bool
    {
        return $user->isManager() || $user->isAdmin();
    }

    public static function canEdit(User $user, TravelRequest $travelRequest): bool
    {
        return ((int) $user->id == (int) $travelRequest->user_id) ? true : false;
    }

    public static function canReopen(User $user, TravelRequest $travelRequest): bool
    {
        return ($travelRequest->isClosed() && $user->isAdmin()) ? true : false;
    }

    public static function canApprove(User $approver, TravelRequest $travelRequest): bool
    {
        $theRequestWasNotMadeByApprover = ($travelRequest->user_id == $approver->id) ? false : true;

        return ($approver->isAdmin() || $approver->isManager()) && ($theRequestWasNotMadeByApprover || $approver->isAdmin()) && $travelRequest->isPending();
    }

    public static function canCancel(User $approver, TravelRequest $travelRequest): bool
    {
        $approverIsHighLevel = ($approver->isManager() || $approver->isAdmin()) ? true : false;
        $theRequestWasNotMadeByApprover = ($travelRequest->user_id == $approver->id) ? false : true;

        return ($theRequestWasNotMadeByApprover || $approver->isAdmin()) && $approverIsHighLevel;
    }

    public function before(User $user, string $abitily):?bool
    {
        if($user->isAdmin()){
            return true;
        }

        return null;
    }
}