<template>

    <div class="card">

        <table class="table card-table table-hover">
            <thead>
                <tr>
                    <th v-for="column in columns" v-if="column.visible">{{ column.label }}</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="row in rows">
                    <component v-for="column in columns" v-if="column.visible"
                               :is="column.component"
                               :key="column.key"
                               :row="row">
                    </component>
                </tr>
            </tbody>
        </table>

    </div>

</template>

<script>
    export default {
        name: "card-result-table",
        props: {
            columns: Array,
            rows: Array,
        }
    }
</script>

<style scoped>

</style>