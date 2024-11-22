<?php

namespace App\Repositories\UserProfileAction;

use App\Enums\UserProfileActionTypeEnum;
use App\Models\Profile;
use App\Models\ProfileMessage;
use App\Models\UserProfileAction;
use App\Services\DTO\Profile\GetUserPairsDto;
use Illuminate\Support\Collection;

class UserProfileActionRepository implements UserProfileActionInterface
{
    public function getUserPairs(array $userIds, GetUserPairsDto $dto): Collection
    {
        $currentUserId = auth()->id();

        $currentUserProfile = Profile::query()->where('user_id', $currentUserId)->first();

        $userPairs = UserProfileAction::query()
            ->where('profile_id', $currentUserProfile->id)
            ->where('type', UserProfileActionTypeEnum::LIKE->value)
            ->whereIn('user_id', $userIds)
            ->get(['user_id']);

        $userIds = $userPairs->pluck('user_id')->toArray();

        $ids =  array_merge([$currentUserId], $userIds);

        $lastMessages = ProfileMessage::query()
            ->where(function ($query) use ($currentUserId, $ids) {
                $query->where(function ($query) use ($currentUserId, $ids) {
                    $query->whereIn('id_from', $ids)
                        ->where('id_to', $currentUserId);
                })->orWhere(function ($query) use ($currentUserId, $ids) {
                    $query->whereIn('id_to', $ids)
                        ->where('id_from', $currentUserId);
                });
            })
            ->get()
            ->groupBy(function ($message) use ($currentUserId) {
                return $message->id_from == $currentUserId ? $message->id_to : $message->id_from;
            })
            ->map(function ($group) {
                $lastMessage = $group->sortByDesc('created_at_timestamp')->first();

                return [
                    'message_text' => $lastMessage->message_text,
                    'created_at_timestamp' => $lastMessage->created_at_timestamp,
                ];
            });

        return $userPairs->map(function ($pair) use ($lastMessages) {
            $lastMessage = $lastMessages[$pair->user_id] ?? null;
            $pair->last_message = $lastMessage['message_text'] ?? null;
            $pair->last_message_date = $lastMessage['created_at_timestamp'] ?? null;

            return $pair;
        });
    }
}
