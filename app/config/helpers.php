<?php
function dd($data): void {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
   exit;
function getTransactionColor(string $type): string {
    return match($type) {
        'depot' => 'text-blue-600',
        'retrait' => 'text-orange-600',
        'paiement' => 'text-red-500',
        default => 'text-gray-600',
    };
}

function getTransactionBg(string $type): string {
    return match($type) {
        'depot' => 'bg-blue-500',
        'retrait' => 'bg-[#a65413]',
        'paiement' => 'bg-pink-500',
        default => 'bg-gray-400',
    };
}


}