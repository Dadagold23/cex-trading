<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditService
{
    public function log(
        ?User $actor,
        string $action,
        string $module,
        ?int $targetId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null
    ): AuditLog {
        return AuditLog::create([
            'actor_id' => $actor?->id,
            'actor_type' => $actor ? 'user' : null,
            'action' => $action,
            'module' => $module,
            'target_id' => $targetId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
