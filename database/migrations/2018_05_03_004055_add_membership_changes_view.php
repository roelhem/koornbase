<?php

use App\Enums\MembershipStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMembershipChangesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $novices = DB::table('memberships')
            ->select('person_id','id AS membership_id','application AS date')
            ->addSelect(DB::raw(MembershipStatus::Novice.' AS status'))
            ->whereNotNull('application');

        $members = DB::table('memberships')
            ->select('person_id','id AS membership_id','start AS date')
            ->addSelect(DB::raw(MembershipStatus::Member.' AS status'))
            ->whereNotNull('start');

        $formerMembers = DB::table('memberships')
            ->select('person_id','id AS membership_id','end AS date')
            ->addSelect(DB::raw(MembershipStatus::FormerMember.' AS status'))
            ->whereNotNull('end');

        $novices->union($members)->union($formerMembers);

        \Jwz104\EloquentView\Facades\EloquentView::create('membership_status_changes', $novices);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Jwz104\EloquentView\Facades\EloquentView::dropIfExists('membership_status_changes');
    }
}
