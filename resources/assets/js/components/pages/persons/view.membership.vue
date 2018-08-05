<template>

    <b-container>
        <b-row>
            <b-col lg="6">
                <tabler-card title="Lidmaatschap" status="red" collapsible>
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-badge bg-yellow"></div>
                            Inschrijving
                            <div class="timeline-time">12 januari 2018</div>
                        </li>

                        <li class="timeline-item">
                            <span class="text-muted-dark">Kennismakerstijd</span>
                            <div class="timeline-time">3 maanden</div>
                        </li>

                        <li class="timeline-item">
                            <div class="timeline-badge bg-green"></div>
                            Inauguratie
                            <div class="timeline-time">12 februari 2019</div>
                        </li>

                        <li class="timeline-item">
                            <span class="text-muted-dark">Lidmaatschap</span>
                            <div class="timeline-time">1 jaar</div>
                        </li>

                        <li class="timeline-item">
                            <div class="timeline-badge bg-red"></div>
                            Uitschrijving
                            <div class="timeline-time">1 september 2020</div>
                        </li>

                        <li class="timeline-item">
                            <span class="text-muted-dark">Oud-lid</span>
                            <div class="timeline-time">1 jaar</div>
                        </li>
                    </ul>
                </tabler-card>
            </b-col>
        </b-row>


    </b-container>

</template>

<script>
    import TablerCard from "../../TablerCard";

    export default {
        components: {TablerCard},
        name: "page-person-membership"
    }
</script>

<style scoped>

</style>
