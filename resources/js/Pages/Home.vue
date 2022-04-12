<template>
  <div>
    <apexchart
      width="500"
      type="bar"
      :options="chartOptions"
      :series="series"
    ></apexchart>
  </div>
  <button @click="read">Test</button>
</template>

<script>
import axios from 'axios';
import VueApexCharts from "vue3-apexcharts";

export default {
    components: {
    apexchart: VueApexCharts,
  },
  data: function() {
    return {
      chartOptions: {
        chart: {
          id: "vuechart-example",
        },
        xaxis: {
          categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998],
        },
      },
      series: [
        {
          name: "series-1",
          data: [30, 40, 35, 50, 49, 60, 70, 91],
        },
      ],
    };
  },
  methods: {
      async read() {
          //axios.get('/sanctum/csrf-cookie').then(response => {
         //     console.log(response);
        //});
        axios.get('/api/tours').then(function(response) {
            console.log(response);
        })
        .catch(function(error){
            console.log(error);
        })
          //const { data } = window.axios.get('/api/tours');
          //console.log(data);
      }
  }
};
</script>

<style>

</style>