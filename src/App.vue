<template>
  <div id="app">
    <el-table :data="list">
      <el-table-column
      prop="date"
        label="日期"
        >
      </el-table-column>

    </el-table>

  </div>
</template>

<script>

import {config} from './config.js'
export default {
  name: 'App',
  data () {
    return {
      list: []

    }
  },
  mounted () {
    this.getlist()
  },

  methods: {
    async     getlist (year) {
      this.list = []
      if (!year) {
        year = new Date().getFullYear()
      }
      let startdate = this.$moment(year + '-01-01')
      let enddate = this.$moment(year + '-12-31')
      while (startdate <= enddate) {
        let url = config.api + startdate.format('YYYYMMDD')
        let res = await fetch(url)
        let data = await res.json()
        if (data && res.data) {
          let record = Object.assign({ 'date': startdate.format('YYYY年MM月DD日') }, res.data)
          this.list.push(record)
        }
        startdate = this.$moment(startdate).add(1, 'days') // 日期加1
      }
    }
  }

}
</script>

<style scoped>
</style>
