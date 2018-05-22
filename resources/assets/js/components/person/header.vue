<template>

    <b-container style="margin-top: -40px;">

        <div class="d-flex">
            <div class="person-avatar px-2">
                <base-avatar v-bind="person.avatar" size="xxl" default-color="blue"></base-avatar>
            </div>

            <div class="person-name p-1 pt-3">
                <h1 class="m-1">
                    <span>{{ person.name.first }}</span>
                    <span class="small font-italic" v-if="person.name.nickname">[ {{ person.name.nickname }} ]</span>
                    <span v-if="person.name.prefix">{{ person.name.prefix }}</span>
                    <span v-if="person.name.last">{{ person.name.last }}</span>
                </h1>
                <div class="tags">
                    <ref-tag-group v-for="m in person.groupMemberships" :key="m.id" :val="m.group.id" allow-load label="member_name" />
                </div>
            </div>

        </div>

    </b-container>


</template>

<script>
    import BaseAvatar from "../BaseAvatar";
    import RefTagGroup from "../RefTagGroup";

    export default {
        components: {BaseAvatar, RefTagGroup},
        props: {
            person:Object,
        },
    }
</script>

<style scoped>

</style>