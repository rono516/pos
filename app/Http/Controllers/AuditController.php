<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = DB::table('audits')
            ->leftJoin('users', 'audits.user_id', '=', 'users.id')
            ->select('audits.*', 'users.first_name as user_name')
            ->orderBy('audits.created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('audits.created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('audits.created_at', '<=', $request->end_date);
        }

        $audits = $query->paginate(20)->through(function ($audit) {
            $modelClass = $audit->auditable_type;
            $modelId    = $audit->auditable_id;

            $display = null;

            if (class_exists($modelClass)) {
                $model = app($modelClass)->find($modelId);
                if ($model) {
                    $display = method_exists($model, 'getAuditDisplayName')
                    ? $model->getAuditDisplayName()
                    : ($model->name ?? $model->title ?? 'ID: ' . $model->id);
                }
            }

            $audit->model_display = $display ?? 'Deleted or Unknown';
            return $audit;
        });

        return view('audits.index', compact('audits'));
    }
}
