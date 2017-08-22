<template>
    <div class="columns">

      <div class="column is-two-thirds">

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
              <input autofocus class="input" type="text" placeholder="Type your message" v-model="body" @keyup.enter="store()">
            </p>
            <p class="control">
              <a class="button is-info" @click="store()">
                Send
              </a>
            </p>
          </div>

          <div class="field" v-if="!isJoined">
            <div class="control has-icons-left">
              <input autofocus class="input"  v-bind:class="{'is-danger': hasError }" type="text" placeholder="Enter your name" v-model="username" @keyup.enter="join()">
              <span class="icon is-small is-left">
                <i class="fa fa-user"></i>
              </span>
            </div>
            <p class="help is-danger" v-if="usernameError != ''">{{ usernameError }}</p>
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
    export default {
        data() {
            return {
                messages: [{
                  body: 'some message',
                  username: 'petr'
                }],
                members: [],
                username: '',
                hasError: false,
                usernameError: '',
                isJoined: false,
                body: ''
            }
        },

        mounted() {
            this.listenForNewMessage();
        },


        methods: {
            store() {
                axios.post('/messages', {body: this.body, username: this.username})
                  .then((response) => {
                    this.body = '';
                    this.messages.push(response.data.message);
                  });
            },

            check() {
              axios.post('auth/check')
                    .then((response) => {
                      console.log(response.data);
                    });
            },

            join() {
              axios.post('/join', {username: this.username})
              .then((response) => {
                console.log(response.data);
                  this.listenForMembers();
                  this.isJoined = true;
              })
              .catch(error => {
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
                      console.log('joining user: ' + user.name);
                  })
                  .leaving((user) => {
                      this.members = this.members.filter( member => {
                        return member.id !== user.id
                      });
                      console.log('leaving user: ' + user.name);
                  });
            }
        }
    }
</script>
