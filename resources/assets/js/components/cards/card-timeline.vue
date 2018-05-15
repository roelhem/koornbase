<template>

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Tijdlijn</h3>
        </div>

        <div class="card-body">
            <ul class="timeline">
                <li class="timeline-item" v-for="entry in entries">
                    <div class="timeline-badge" :class="entry.badge"></div>
                    {{ entry.label }}
                    <div class="timeline-time">{{ entry.date | moment("dddd, MMMM Do YYYY") }}</div>
                </li>
            </ul>
        </div>

    </div>

</template>

<script>
    export default {
        name: "card-timeline",
        props: {
            entries:Object
        }
    }
</script>

<style scoped>

</style>