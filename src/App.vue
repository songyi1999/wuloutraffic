<template>
  <div id="app">
    <div style="margin:1rem;">
      <el-button type="primary" size="default" @click="dlexcel">导出excel</el-button>

    </div>

    <el-table
    height="600"
    :data="list">
      <el-table-column
      prop="date"
        label="日期"
      />
      <el-table-column
        prop="todayflow"
        label="今日流量"
      />
      <el-table-column
        prop="localflow"
        label="本地人次"
      />
      <el-table-column

        label="外地人次"
        >
      <template slot-scope="scope">
        {{scope.row.flowflow-scope.row.localflow}}
      </template>
      </el-table-column>
      <el-table-column
        prop="flowflow"
        label="今日人次"
      />

    </el-table>

  </div>
</template>

<script>
import XLSX from 'xlsx'
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
    dlexcel () {
      let data = []
      let header = ['日期', '今日流量', '本地人次', '外地人次', '今日人次']
      data.push(header)
      this.list.forEach((item) => {
        let row = []
        row.push(item.date)
        row.push(item.todayflow)
        row.push(item.localflow)
        row.push(item.flowflow - item.localflow)
        row.push(item.flowflow)
        data.push(row)
      })
      let ws = XLSX.utils.aoa_to_sheet(data)
      let wb = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(wb, ws, 'SheetJS')
      XLSX.writeFile(wb, 'sheetjs.xlsx')
    },

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
        // console.log(data)
        if (data && data.data) {
          let record = Object.assign({ 'date': startdate.format('YYYY年MM月DD日') }, data.data)
          this.list.push(record)
          console.log(record)
        }
        startdate = this.$moment(startdate).add(1, 'days') // 日期加1
      }
      this.$message({
        message: '数据加载完成',
        type: 'success'
      })
    }
  }

}
</script>

<style scoped>
</style>
