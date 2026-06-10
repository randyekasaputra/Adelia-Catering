<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

if ($request->isMethod('post')) {
    $id1 = $request->input('id1');
    $id2 = $request->input('id2');

    $m1 = \App\Models\Menu::find($id1);
    $m2 = \App\Models\Menu::find($id2);

    if ($m1 && $m2) {
        $temp = $m1->image;
        $m1->image = $m2->image;
        $m2->image = $temp;
        $m1->save();
        $m2->save();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Menu not found']);
    }
}
