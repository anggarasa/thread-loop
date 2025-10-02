<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FollowController extends Controller
{
    /**
     * Follow a user
     */
    public function follow(User $user): JsonResponse
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself.'
            ], 400);
        }

        if ($currentUser->follows($user)) {
            return response()->json([
                'success' => false,
                'message' => 'You are already following this user.'
            ], 400);
        }

        $currentUser->follow($user);

        return response()->json([
            'success' => true,
            'message' => 'Successfully followed ' . $user->name,
            'followers_count' => $user->followersCount()
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user): JsonResponse
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot unfollow yourself.'
            ], 400);
        }

        if (!$currentUser->follows($user)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not following this user.'
            ], 400);
        }

        $currentUser->unfollow($user);

        return response()->json([
            'success' => true,
            'message' => 'Successfully unfollowed ' . $user->name,
            'followers_count' => $user->followersCount()
        ]);
    }

    /**
     * Toggle follow status
     */
    public function toggle(User $user): JsonResponse
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself.'
            ], 400);
        }

        if ($currentUser->follows($user)) {
            $currentUser->unfollow($user);
            $message = 'Successfully unfollowed ' . $user->name;
            $isFollowing = false;
        } else {
            $currentUser->follow($user);
            $message = 'Successfully followed ' . $user->name;
            $isFollowing = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_following' => $isFollowing,
            'followers_count' => $user->followersCount()
        ]);
    }
}
