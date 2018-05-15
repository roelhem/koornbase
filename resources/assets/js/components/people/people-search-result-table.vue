<template>

    <div class="card">
        <div class="dimmer" :class="{'active':isLoading}">
            <div class="loader"></div>
            <div class="dimmer-content">
                <table class="table card-table table-hover">
                    <thead>
                    <tr>
                        <th v-show="columns.avatar.visible">&nbsp;</th>
                        <th v-show="columns.id.visible">ID</th>
                        <th v-show="columns.name.visible">Naam</th>
                        <th v-show="columns.name_short.visible">Korte Naam</th>
                        <th v-show="columns.birth_date.visible">Geboortedatum</th>
                        <th v-show="columns.membership_status.visible">Status</th>
                        <th v-show="columns.groups.visible">Groepen</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="result in results">
                        <td v-show="columns.avatar.visible"><span class="avatar avatar-blue">{{ result.avatar.letters }}</span></td>
                        <td v-show="columns.id.visible">{{ result.id }}</td>
                        <td v-show="columns.name.visible">
                            <div>
                                {{ result.name.full }}
                            </div>
                            <div class="text-muted small">
                                {{ result.name.initials }} {{ result.name.prefix }} {{ result.name.last }}
                            </div>
                        </td>
                        <td v-show="columns.name_short.visible">{{ result.name.short }}</td>
                        <td v-show="columns.birth_date.visible">
                            <div>{{ result.birth_date | moment('D MMMM Y') }}</div>
                            <div class="small font-italic" :class="{'text-danger': getAge(result.birth_date) < 18, 'text-muted': getAge(result.birth_date) >= 18}">
                                ( {{ getAge(result.birth_date) }} jaar )
                            </div>
                        </td>
                        <td v-show="columns.membership_status.visible">
                            <div>
                                <membership-status :value="result.membership.status"></membership-status>
                            </div>
                            <div v-if="result.membership.since" class="text-muted small">
                                {{ result.membership.since | moment('dd D MMMM Y') }}
                            </div>
                        </td>
                        <td v-show="columns.groups.visible">
                            <div class="tags">
                                <group-tag v-for="groupMembership in result.groupMemberships" :id="groupMembership.group_id"></group-tag>
                            </div>
                        </td>
                        <td class="w-1">
                            <a class="icon" :href="result.links.show">
                                <i class="fe fe-more-vertical"></i>
                            </a>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>



    </div>

</template>

<script>
    import moment from 'moment';

    export default {
        name: "people-search-result-table",
        props: {
            'columns':Object,
            'results':Array,
            'isLoading':Boolean
        },
        methods: {
            getAge(birth_date) {
                return moment().diff(birth_date, 'years');
            }
        }
    }
</script>

<style scoped>

</style>