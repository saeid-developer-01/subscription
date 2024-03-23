<?php

namespace IICN\Subscription\Commands;


use IICN\Subscription\Constants\Status;
use IICN\Subscription\Models\SubscriptionTransaction;
use IICN\Subscription\Services\Purchase\Appstore;
use IICN\Subscription\Services\Purchase\Playstore;
use IICN\Subscription\Services\Purchase\Purchase;
use IICN\Subscription\Services\Response\SubscriptionResponse;
use Illuminate\Console\Command;

class RetryCheckPendingTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = SubscriptionTransaction::query()->whereIn('status', [Status::PENDING, Status::INIT])->get();

        foreach ($transactions as $transaction) {
            if ($transaction->agent_type == 'appStore') {
                $playstore = new Purchase(new Appstore());
            } elseif($transaction->agent_type == 'playStore') {
                $playstore = new Purchase(new Playstore());
            } else {
                return ;
            }

            $playstore->retry($transaction);
        }
    }
}
