<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Follower;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FollowerController extends AbstractController
{
    #[Route('/follow/{id}', name: 'app_follow')]
    public function follow(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $currentUser = $this->getUser();

        if (!$currentUser || $currentUser === $user) {
            return new JsonResponse(['message' => 'You can\'t follow this user'], 403);
        }

        $existingFollow = $entityManager->getRepository(Follower::class)->findOneBy([
            'follower' => $currentUser,
            'followed' => $user
        ]);

        if ($existingFollow) {
            $entityManager->remove($existingFollow);
            $entityManager->flush();
            return new JsonResponse(['message' => 'User unfollowed'], 204);
        } else {
            $follow = new Follower();
            $follow->setFollower($currentUser);
            $follow->setFollowed($user);
            $follow->setCreatedAt(new \DateTimeImmutable());
            
            $entityManager->persist($follow);
            $entityManager->flush();
            return new JsonResponse(['message' => 'User followed'], 201);
        }
        // return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
    }
}
