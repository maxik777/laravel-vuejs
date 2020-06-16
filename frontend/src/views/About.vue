<template>
  <div>
    <div style="display: flex; justify-content: space-between; padding-bottom: 5px">
      <Button type="success" @click="showAddModal">Add new post</Button>
      <div style="opacity: 0">Middle</div>
      <div style="opacity: 0">Right</div>
    </div>

    <Table border :columns="columns12" :data="posts">
      <template slot-scope="{ row }" slot="name">
        <strong>{{ row.name }}</strong>
        <strong>{{ row.description }}</strong>
        <strong>{{ row.created_at }}</strong>
      </template>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="primary" size="small" style="margin-right: 5px" @click="showEditModal(row,index)">View</Button>
        <Button type="error" size="small" @click="modal2=true">Delete</Button>
      </template>
    </Table>
    <!--EDIT MODAL HTML-->
    <Modal
            v-model="editModal"
            title="Edit post"
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
    <!--ADD MODAL HTML-->
    <Modal
            v-model="addModal"
            title="Add post"
            :mask-closable="false"
            :closable="false"

    >
      <div style="padding-bottom: 15px">
        <p><strong>Name</strong></p>
        <Input v-model="editData.name" placeholder="Add post name"  />
      </div>

      <p><strong>Description</strong></p>
      <Input type="textarea" :rows="6"  v-model="editData.description" placeholder="Add post description"  />
      <div slot="footer">
        <Button type="default" @click="addModal=false">Close</Button>
        <Button type="primary" @click="add" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add'}}</Button>
      </div>
    </Modal>

    <!--DELETE MODAL-->
    <Modal v-model="modal2" width="360">
      <p slot="header" style="color:#f60;text-align:center">
        <Icon type="ios-information-circle"></Icon>
        <span>Delete confirmation</span>
      </p>
      <div style="text-align:center">
        <p>After this task is deleted, the downstream 10 tasks will not be implemented.</p>
        <p>Will you delete it?</p>
      </div>
      <div slot="footer">
        <Button type="error" size="large" long :loading="modal_loading" @click="del">Delete</Button>
      </div>
    </Modal>

  </div>
</template>

<script>

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
            title: 'Created At',
            key: 'created_at',
            width: 200,
            align: 'center'
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
        addModal : false,
        modal2:false,
        isAdding : false,
      }
    },




    methods: {
      async all(){
        const res = await this.callApi('get', 'http://127.0.0.1:8000/api/posts')
        if(res.status==200){
          this.posts = res.data
        }else{
          this.swr()
        }
      },

      async update(id){
        if(this.editData.name.trim()=='') return this.e('Name is required')
        if(this.editData.description.trim()=='') return this.e('Description is required')
        const res = await this.callApi('post', 'http://127.0.0.1:8000/api/edit/'+id,
                this.editData
        )
        if(res.status===200){
          this.posts.unshift(res.data)
          this.s('Tag has been added successfully!')
          this.editModal = false
          this.all()
        }else{
          if(res.status==422){
            if(res.data.errors.name && res.data.errors.description){
              this.e(res.data.errors.name[0])
              this.e(res.data.errors.description[0])
            }

          }else{
            this.swr()
          }

        }
      },

      async add(){
        if(this.editData.name.trim()=='') return this.e('Name is required')
        if(this.editData.description.trim()=='') return this.e('Description is required')
        const res = await this.callApi('post', 'http://127.0.0.1:8000/api/add',
                this.editData
        )
        if(res.status===200){
          this.posts.unshift(res.data)
          this.s('Tag has been added successfully!')
          this.addModal = false
          this.all()
        }else{
          if(res.status==422){
            if(res.data.errors.name && res.data.errors.description){
              this.e(res.data.errors.name[0])
              this.e(res.data.errors.description[0])
            }

          }else{
            this.swr()
          }

        }
      },
      async del(id){
        const res = await this.callApi('post', 'http://127.0.0.1:8000/api/edit/'+id,
                this.editData
        )
        if(res.status===200){
          this.posts.unshift(res.data)
          this.s('Tag has been added successfully!')
          this.editModal = false
          this.all()
        }else{
          if(res.status==422){
            if(res.data.errors.name && res.data.errors.description){
              this.e(res.data.errors.name[0])
              this.e(res.data.errors.description[0])
            }

          }else{
            this.swr()
          }

        }
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

      showAddModal(){
        let obj = {
          name: '',
          description : ''
        }
        this.editData = obj
        this.addModal = true
      },

    },

    created: async function(){
      const res = await this.callApi('get', 'http://127.0.0.1:8000/api/posts')
      if(res.status==200){
        this.posts = res.data
      }else{
        this.swr()
      }
    },

  }
</script>
