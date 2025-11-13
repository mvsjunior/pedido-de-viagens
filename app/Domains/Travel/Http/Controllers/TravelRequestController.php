<?php

namespace App\Domains\Travel\Http\Controllers;

use App\Domains\Travel\Actions\{ApproveTravelRequest, CancelTravelRequest, EditTravelRequest,ListTravelRequests,OpenATravelRequest, ReopenTravelRequest, ShowTravelRequest};
use App\Domains\Travel\DTO\EditTravelRequestDTO;
use App\Domains\Travel\Exceptions\{DepartureDateIsLaterThanReturnDate,NotAuthorizedToApprove, NotAuthorizedToCancel, TravelHasAlreadyBeenApproved, TravelHasAlreadyBeenCanceled, TravelRequestNotFound,UserCannotEditThisRequest,UserNotFound};
use App\Domains\Travel\Http\Requests\{CancelTravelRequestRequest, CreateTravelRequestRequest,EditTravelRequestRequest};
use App\Domains\Travel\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TravelRequestController extends Controller {

    public function open(CreateTravelRequestRequest $request, OpenATravelRequest $action): JsonResponse
    {
        try {
            $action->handle($request->toDTO());
        } catch (\Throwable $th) {
            Log::error("Ocorreu um erro ao abrir um novo pedido de viagem.", $th->getTrace());
            return response()->json(["message" => 'An error occurred while submitting the travel request.' . $th->getMessage()]);
        }

        return response()->json(["message" => 'The travel request has been submitted.']);
    }

    public function list(Request $request, ListTravelRequests $action)
    {
        $requests = $action->handle(User::find((int) $request->user()->id));

        return response()->json($requests);
    }

    public function show(Request $request, ShowTravelRequest $action): JsonResponse
    {
        $travel = $action->handle((int) $request->user()->id, (int) $request->id);

        return response()->json($travel);
    }

    public function edit( EditTravelRequestRequest $request, EditTravelRequest $action): JsonResponse
    {
        $message = '';
        $code = 200;

        try{
            $travel = $action->handle((int) $request->user()->id, EditTravelRequestDTO::createFromRequest($request));
        }catch (UserCannotEditThisRequest $e){
            $message = 'unauthorized.';
            $code = 401;
        }catch (DepartureDateIsLaterThanReturnDate $e){
            $message = 'The departure date cannot be later than the return date.';
            $code = 422;
        }catch (Throwable $th){
            Log::error($th->getMessage(), $th->getTrace());
            $message = 'An unknown error has occurred. Please contact support.';
            $code = 500;
        }

        return $code != 200 ? response()->json(['message' => $message], $code) : response()->json($travel);
    }

    public function approve(Request $request, ApproveTravelRequest $action){

        $approverId = (int) $request->user()->id;
        $travelRequestId = (int) $request->id;

        try{
            $action->handle($approverId, $travelRequestId);
        }catch(TravelHasAlreadyBeenApproved $e){
            return response()->json(['message' => $e->getMessage()],409);
        }catch(NotAuthorizedToApprove $e){
            return response()->json(['message' => $e->getMessage()],401);
        }catch(UserNotFound $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }catch(TravelRequestNotFound $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Travel Request has approved.']);
    }

    public function cancel(CancelTravelRequestRequest $request, CancelTravelRequest $action){

        $cancellerId = (int) $request->user()->id;
        $travelRequestId = (int) $request->id;
        $reason = $request->post('cancelReason');

        try{
            $action->handle($cancellerId, $travelRequestId, $reason);
        }catch(TravelHasAlreadyBeenCanceled $e){
            return response()->json(['message' => $e->getMessage()],409);
        }catch(NotAuthorizedToCancel $e){
            return response()->json(['message' => $e->getMessage()],401);
        }catch(UserNotFound $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }catch(TravelRequestNotFound $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Travel Request has been canceled.']);
    }
}