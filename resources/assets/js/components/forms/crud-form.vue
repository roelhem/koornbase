<template>

    <form class="card" :action="action" method="post">

        <div class="card-header">
            <h3 class="card-title">{{ title }}</h3>
        </div>

        <div class="card-body">
            <slot></slot>
        </div>

        <div class="card-footer text-right">
            <div class="d-flex">
                <button type="submit" class="btn btn-primary ml-auto">{{ submitText || 'Opslaan' }}</button>
            </div>
        </div>


    </form>

</template>

<script>
    export default {
        name: "crud-form",
        props: ['action','title', 'submitText']
    }
</script>

<style scoped>

</style>