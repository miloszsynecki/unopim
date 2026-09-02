<?php

namespace Webkul\Orders\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Webkul\Orders\DataGrids\OrderDataGrid;
use Webkul\Orders\Enums\OrderStatus;
use Webkul\Orders\Models\OrderProxy;

class OrderController extends Controller
{
    public function index(): View|JsonResponse
    {
        abort_unless(bouncer()->hasPermission('sales.orders.view'), 403);

        if (request()->ajax()) {
            return resolve(OrderDataGrid::class)->toJson();
        }

        $activeChannel = request('channel') ?: null;

        // Status counts respect the active channel scope, so the rails agree.
        $counts = DB::table('orders')
            ->when($activeChannel, fn ($q) => $q->where('channel', $activeChannel))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $channelCounts = DB::table('orders')
            ->selectRaw('channel, count(*) as total')
            ->groupBy('channel')
            ->pluck('total', 'channel');

        $active = in_array(request('status'), array_column(OrderStatus::cases(), 'value'), true)
            ? request('status')
            : null;

        return view('orders::admin.index', [
            'counts'        => $counts,
            'total'         => $counts->sum(),
            'active'        => $active,
            'channelCounts' => $channelCounts,
            'activeChannel' => $activeChannel,
        ]);
    }

    public function view(int $id): View
    {
        abort_unless(bouncer()->hasPermission('sales.orders.view'), 403);

        $order = OrderProxy::modelClass()::with('items')->findOrFail($id);

        return view('orders::admin.view', compact('order'));
    }
}
