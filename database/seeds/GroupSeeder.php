<?php

use Illuminate\Database\Seeder;
use App\GroupCategory;
use App\Group;
use Symfony\Component\Yaml\Yaml;

class GroupSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws
     */
    public function run()
    {

        $values = Yaml::parseFile(__DIR__.'/init-data/groups.yaml');

        // GROUP CATEGORIES
        foreach ($values as $groupCategory) {
            $attrs = array_only($groupCategory, [
                'name','name_short','slug','description','style','is_required','options'
            ]);
            $category = GroupCategory::create($attrs);
            if(!($category instanceof GroupCategory)) {
                abort(500);
            }

            // GROUP-CATEGORY RBAC-ASSIGNMENTS
            $assignValues = array_get($groupCategory, 'assign',[]);
            foreach ($assignValues as $assignValue) {
                $category->assignNode($assignValue);
            }

            // GROUPS
            $groupValues = array_get($groupCategory, 'groups', []);
            foreach ($groupValues as $groupValue) {
                $attrs = array_only($groupValue, [
                    'name','name_short','slug','description','member_name','is_required'
                ]);
                $group = $category->groups()->create($attrs);
                if(!($group instanceof Group)) {
                    abort(500);
                }

                // GROUP EMAIL ADDRESSES
                $emailValues = array_get($groupValue, 'email_addresses', []);
                foreach ($emailValues as $emailValue) {
                    if(is_string($emailValue)) {
                        $emailValue = [
                            'email_address' => $emailValue
                        ];
                    }

                    $group->emailAddresses()->create($emailValue);
                }

                // GROUP RBAC-ASSIGNMENTS
                $assignValues = array_get($groupValue, 'assign', []);
                foreach ($assignValues as $assignValue) {
                    $group->assignNode($assignValue);
                }
            }
        }

        /*
        foreach ($this->defaultValues() as $cat) {
            $category = GroupCategory::create(array_except($cat, ['groups']));

            foreach (array_get($cat, 'groups',[]) as $grp) {
                $group = Group::make(array_except($grp,['titles']));
                $category->groups()->save($group);

                $group->emailAddresses()->create([
                    'email_address' => "{$group->slug}@koornbeurs.nl"
                ]);

            }
        }
        */

    }
}
