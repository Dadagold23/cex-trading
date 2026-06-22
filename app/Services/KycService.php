<?php

namespace App\Services;

use App\Models\KycRecord;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class KycService
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function isApproved(User $user): bool
    {
        return $user->kycRecords()
            ->where('status', 'approved')
            ->exists();
    }

    public function submit(User $user, array $data): KycRecord
    {
        $frontImage = $this->storeFile($data['front_image'] ?? null, 'kyc');
        $backImage = $this->storeFile($data['back_image'] ?? null, 'kyc');
        $selfieImage = $this->storeFile($data['selfie_image'] ?? null, 'kyc');
        $addressDocument = $this->storeFile($data['address_document'] ?? null, 'kyc');

        $record = KycRecord::updateOrCreate(
            ['user_id' => $user->id],
            [
                'document_type' => $data['document_type'],
                'document_number' => $data['document_number'] ?? null,
                'front_image' => $frontImage,
                'back_image' => $backImage,
                'selfie_image' => $selfieImage,
                'address_document' => $addressDocument,
                'status' => 'pending',
                'submitted_at' => now(),
                'reviewed_by' => null,
                'reviewed_at' => null,
                'rejection_reason' => null,
            ]
        );

        $this->notificationService->send(
            $user,
            'KYC Submitted',
            'Your KYC documents have been submitted and are awaiting admin review.',
            'kyc'
        );

        return $record;
    }

    public function approve(KycRecord $record, User $admin): KycRecord
    {
        $record->update([
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        $this->notificationService->send(
            $record->user,
            'KYC Approved',
            'Your identity verification has been approved successfully.',
            'kyc'
        );

        return $record->fresh(['user', 'reviewer']);
    }

    public function reject(KycRecord $record, User $admin, string $reason): KycRecord
    {
        $record->update([
            'status' => 'rejected',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);

        $this->notificationService->send(
            $record->user,
            'KYC Rejected',
            "Your KYC submission was rejected. Reason: {$reason}",
            'kyc'
        );

        return $record->fresh(['user', 'reviewer']);
    }

    protected function storeFile(?UploadedFile $file, string $folder): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        return $file->store($folder, 'public');
    }
}
