<script >
	import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';
    import Highlight from '../components/Highlight.vue';

	export default
	{
		components: { Replies, SubscribeButton, Highlight },
		props: ['thread'],
		data()
		{
		    return {
		        repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                pinned: this.thread.pinned,
			    editing: false,
			    title: this.thread.title,
			    body: this.thread.body,
                form: {},
            };
		},
		created()
		{
            this.resetForm();
		},
		methods:
		{
            toggleLock()
		    {
                var url = '/locked-threads/' + this.thread.slug;

                axios[ this.locked ? 'delete' : 'post' ]( url );

                this.locked = !this.locked;
            },
			togglePin()
			{
			    var url = '/pinned-threads/' + this.thread.slug;

                axios[ this.pinned ? 'delete' : 'post' ]( url );

                this.pinned = !this.pinned;
			},
			classes( target )
			{
			    return [
			        'btn',
				    target ? 'btn-outline-danger' : 'btn-outline-primary'
			    ];
			},
			update()
			{
                var url = '/threads/' + this.thread.channel.slug + '/' + this.thread.slug;

                axios.patch( url, this.form ).then(() =>
                {
			       this.editing = false;
			       this.title = this.form.title;
                   this.body  = this.form.body;

			       flash( 'Your thread has been updated!' );
			    } );
			},
			resetForm()
			{
			    this.form =
			    {
			        title: this.thread.title,
				    body: this.thread.body
			    };

			    this.editing = false;
			}
		}
	}
</script >