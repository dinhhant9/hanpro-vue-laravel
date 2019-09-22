<template>
  <v-data-table
    :headers="headers"
    :items="events"
    :loading="tableLoading"
    :disable-sort="true"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-dialog v-model="dialog" persistent max-width="500px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark v-on="on">
              <v-icon class="mr-4">mdi-plus</v-icon>Thêm sự kiện mới
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="12" md="12">
                    <v-text-field
                      v-model="editedItem.name"
                      label="Tên sự kiện"
                      hint='Không được trống'
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="6">
                    <v-menu
                      ref="menu"
                      v-model="menuStart"
                      :close-on-content-click="false"
                      :return-value.sync="date"
                      transition="scale-transition"
                      offset-y
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="editedItem.start"
                          label="Ngày bắt đầu"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="editedItem.start" no-title scrollable>
                        <div class="flex-grow-1"></div>
                        <v-btn text color="primary" @click="menuStart = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.menu.save(date)">OK</v-btn>
                      </v-date-picker>
                    </v-menu>
                  </v-col>
                  <v-col cols="12" sm="6" md="6">
                    <v-select
                      v-model="editedItem.color"
                      :items="colors"
                      item-text="name"
                      item-value="value"
                      label="Màu sắc"
                    ></v-select>
                  </v-col>
                  <v-col cols="12" sm="12" md="12">
                    <v-text-field v-model="editedItem.details" label="Mô tả"></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <div class="flex-grow-1"></div>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" text :disabled="!editedItem.name" :loading="saveLoading" @click="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.start="{ item }">
      <span>{{ handleStart(item.start) }}</span>
    </template>
    <template v-slot:item.details="{ item }">
      <span>{{ item.details || 'Không có mô tả' }}</span>
    </template>
    <template v-slot:item.count="{ item }">
      <span>{{ handleCount(item.start) }}</span>
    </template>
    <template v-slot:item.color="{ item }">
      <v-btn class="mx-2" fab small :color="item.color">
      </v-btn>
    </template>
    <template v-slot:item.action="{ item }">
      <v-btn text icon>
        <v-icon @click="editItem(item)">mdi-pencil</v-icon>
      </v-btn>
      <v-btn text icon>
        <v-icon @click="deleteItem(item)">mdi-delete</v-icon>
      </v-btn>
    </template>
    <template v-slot:no-data>
      <span>Không có dữ liệu</span>
    </template>
  </v-data-table>
</template>

<script>
import { baseAPI } from "@/api/baseAPI";
import moment from "moment";

export default {
  data() {
    return {
      dialog: false,
      tableLoading: false,
      saveLoading: false,
      date: new moment().format("YYYY-MM-DD"),
      menuStart: false,
      menuEnd: false,
      headers: [
        { text: "Tên sự kiện", align: "left", value: "name" },
        { text: "Mô tả", value: "details" },
        { text: "Ngày bắt đầu", value: "start", align: "center" },
        { text: "Đến nay đã", value: "count", align: "center" },
        { text: "Màu sắc", value: "color", align: "center" },
        { text: "Hành động", align: "center", value: "action" }
      ],
      events: [],
      colors: [
          { name: 'Xanh da trời', value: 'blue' },
          { name: 'Tím', value: 'deep-purple' },
          { name: 'Đỏ', value: 'red' },
          { name: 'Xanh lá', value: 'green' },
        ],
      editedIndex: -1,
      editedItem: {
        name: null,
        details: null,
        start: new moment().format("YYYY-MM-DD"),
        color: 'blue'
      },
      defaultItem: {
        name: null,
        details: null,
        start: new moment().format("YYYY-MM-DD"),
        color: 'blue'
      }
    };
  },

  created() {
    this.getAllEvent();
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
    }
  },

  methods: {
    handleStart(start) {
      return new moment(start).format('DD - MM - YYYY')
    },
    handleCount(start) {
      let years = new moment().format('YYYY') - new moment(start).format('YYYY');
      let month = new moment().format('MM') - new moment(start).format('MM');
      if (years == 0 && month != 0) {
        return new moment().format('MM') - new moment(start).format('MM') + ' tháng';
      }
      if (years == 0 && month == 0) {
        return new moment().format('DD') - new moment(start).format('DD') + ' ngày';
      }
      return years + ' năm';
    },
    getAllEvent() {
      this.tableLoading = true;
      baseAPI.get("event").then(res => {
          this.events = res.data;
          this.tableLoading = false;
        })
        .catch(() => {
          this.tableLoading = false;
        });
    },

    editItem(item) {
      this.editedIndex = this.events.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      confirm("Bạn có chắc chắn muốn xoá không?") &&
        baseAPI
          .delete("event/" + item.id)
          .then(() => {
            this.getAllEvent();
          });
    },

    close() {
      this.dialog = false;
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedIndex = -1;
    },

    save() {
      if (this.editedIndex > -1) {
        this.saveLoading = true;
        baseAPI.put("event/" + this.editedItem.id, this.editedItem).then(() => {
            this.saveLoading = false;
            this.close();
            this.getAllEvent();
          })
          .catch(() => {
            this.saveLoading = false;
            this.close();
          });
      } else {
        this.saveLoading = true;
        baseAPI.post("event", this.editedItem).then(() => {
            this.saveLoading = false;
            this.close();
            this.getAllEvent();
          })
          .catch(() => {
            this.saveLoading = false;
            this.close();
          });
      }
    }
  }
};
</script>

<style>
.v-data-table-header-mobile {
  display: none !important;
}
</style>