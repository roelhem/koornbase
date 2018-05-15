<template>

    <div class="card">
        <div class="card-body">
            <div class="media">
                <span class="avatar avatar-xxl mr-5"></span>
                <div class="media-body">
                    <h4 class="m-0">{{ data.name_display }}</h4>
                    <p class="text-muted mb-0">{{ data.email }}</p>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "card-user-small",
        props: {
            data:Object
        }
    }
</script>

<style scoped>

</style>