<template>
    <div class="columns">

      <div class="column is-two-thirds">

      <div class="section" v-if="!isJoined">
          <div class="field">
            <div class="control has-icons-left has-icons-right">
              <input autofocus class="input"  v-bind:class="{'is-danger': hasError }" type="text" placeholder="What's your name?" v-model="username" @keyup.enter="join()">
              <span class="icon is-small is-left">
                <i class="fa fa-user"></i>
              </span>
              <span class="icon is-small is-right" v-if="isLoading">
                <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>
              </span>
            </div>
            <p class="help is-danger" v-if="usernameError != ''">{{ usernameError }}</p>
          </div>
      </div>

      <div class="section">
        <ul>
            <li v-for="message in messages">
              <message :body="message.body" :username="message.username"></message>
            </li>
        </ul>
      </div>

      <div class="section">
          <div class="field has-addons" v-if="isJoined">
            <p class="control is-expanded">
              <input autofocus class="input" type="text" v-focus placeholder="Type your message" v-model="body" @keyup.enter="store()">
            </p>
            <p class="control">
              <a class="button is-info" @click="store()">
                Send
              </a>
            </p>
          </div>
      </div>

      </div>

      <div v-if='members.length' class="section column">
        <article class="message is-dark">
          <div class="message-header">
            <p>Members</p>
          </div>
          <div class="message-body">
            <ul>
              <li v-for="member in members">
                {{ member.name }}
              </li>
            </ul>
          </div>
        </article>

      </div>


    </div>
</template>

<script>
    const focus = {
        inserted(el) {
          el.focus()
        },
      }

    export default {

        directives: { focus },

        data() {
            return {
                messages: [],
                members: [],
                username: '',
                isLoading: false,
                hasError: false,
                usernameError: '',
                isJoined: false,
                body: ''
            }
        },

        mounted() {
            this.listenForNewMessage();
            this.fetchMessages();

        },

        methods: {
            fetchMessages() {
                axios.get('/messages')
                  .then( response => {
                    this.messages = response.data;
                  });
            },

            store() {
                axios.post('/messages', {body: this.body, username: this.username})
                  .then((response) => {
                    this.body = '';
                    this.messages.push(response.data.message);
                  });
            },

            join() {
              this.isLoading = true;
              axios.post('auth/join', {username: this.username})
              .then((response) => {
                  this.isLoading = false;
                  this.listenForMembers();
                  this.isJoined = true;
                  this.hasError = false;
              })
              .catch(error => {
                  this.isLoading = false;
                  this.usernameError = _.flatten(_.toArray(error.response.data))[0];
                  this.hasError = true;
              });
            },

            listenForNewMessage() {
                Echo.channel('chat-room')
                    .listen('MessageWasCreated', (e) => {
                        this.messages.push(e);
                    });
            },

            listenForMembers() {
              Echo.join('chat-presence')
                  .here( members => {
                      this.members = members;
                  })
                  .joining((user) => {
                      this.members.push(user);
                  })
                  .leaving((user) => {
                      this.members = this.members.filter( member => {
                        return member.id !== user.id
                      });
                  });
            }
        }
    }
</script>
