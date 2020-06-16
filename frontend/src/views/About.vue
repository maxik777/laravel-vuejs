<template>
  <div>
    <Table border :columns="columns12" :data="posts">
      <template slot-scope="{ row }" slot="name">
        <strong>{{ row.name }}</strong>
        <strong>{{ row.description }}</strong>
      </template>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="primary" size="small" style="margin-right: 5px" @click="showEditModal(row,index)">View</Button>
        <Button type="error" size="small" @click="remove(index)">Delete</Button>
      </template>
    </Table>
    <Modal
            v-model="editModal"
            title="Edit tag"
            :mask-closable="false"
            :closable="false"

    >
      <div style="padding-bottom: 15px">
        <p><strong>Name</strong></p>
        <Input v-model="editData.name" placeholder="Edit post name"  />
      </div>

      <p><strong>Description</strong></p>
      <Input type="textarea" :rows="6"  v-model="editData.description" placeholder="Edit post description"  />
      <div slot="footer">
        <Button type="default" @click="editModal=false">Close</Button>
        <Button type="primary" @click="update(id)" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit'}}</Button>
      </div>

    </Modal>
  </div>
</template>



<script>
  import axios from 'axios';



  export default {
    data () {
      return {
        columns12: [
          {
            title: 'Id',
            key: 'id',
            width: 100,

          },
          {
            title: 'Name',
            key: 'name'
          },
          {
            title: 'Description',
            key: 'description'
          },
          {
            title: 'Action',
            slot: 'action',
            width: 150,
            align: 'center'
          }
        ],
        posts: [],
        id: '',
        name: '',
        description: '',
        editData : {
          name: '',
          description: ''
        },
        editModal : false,
        isAdding : false,
      }
    },


    created: function(){
      axios.get('http://127.0.0.1:8001/api/posts')
              .then((response) => {
                this.posts = response.data;
              })
              .catch((error) => {
                console.log(error);
              });
    },

    methods: {

      update(id) {
        axios.post(`http://127.0.0.1:8001/api/edit/`+id,{
          name: this.editData.name,
          description: this.editData.description
        })
        .then(function (response) {
          window.history.go()
          console.log(response)

        })
      },

      showEditModal(row, index){
        let obj = {
          name : row.name,
          description : row.description
        }
        this.editData = obj
        this.editModal = true
        this.id = row.id
        this.index = index
      },
    }

  }
</script>
