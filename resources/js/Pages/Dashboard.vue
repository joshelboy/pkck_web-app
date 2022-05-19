<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    
                    <div class="grid grid-cols-2 content-center m-5">
                        <div>
                            <gran-fondo-chart />
                        </div>

                        <div>
                            <year-stacked-chart />
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    //import LineChart from './Strava/LineChart.vue'
    import GranFondoChart from './Strava/GranFondoChart.vue'
    import YearStackedChart from './Strava/YearStackedChart.vue'

    export default defineComponent({
        components: {
            AppLayout,
            //LineChart,
            GranFondoChart,
            YearStackedChart
        },
    })
</script>
