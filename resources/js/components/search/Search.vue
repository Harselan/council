<template>
	<div>
		<div class="flex py-6">
			<div class="mr-10">
				<div class="widget bg-grey-lightest border p-4">
					<h4 class="widget-heading">Search</h4>

					<input type="text"
					       placholder="Search for something.."
					       class="h-full rounded bg-blue-darkest border-none py-1 pl-6 pr-10 text-white"
					       v-model="search"
					       @input="refreshResults()"
					>
				</div>
			</div>

			<div class="w-3/4">
				<results :results="results"></results>
			</div>
		</div>
	</div>
</template>

<script >
	import Results from './Results.vue';

	export default
	{
	    components: { Results },
	    props: ['startSearch'],
		data()
		{
		    return {
		        search: '',
		        results: [],
		    };
		},
		created()
		{
		    if( !this.search.length )
		    {
		        this.search = this.startSearch;
		        this.refreshResults();
		    }
		},
		watch:
		{
		    search( value )
		    {
		        if( !value.length )
		        {
		            this.results = [];
		        }
		    }
		},
		methods:
		{
		    refreshResults()
		    {
		        if( !this.search.length ) return;

		        axios.get( '?q=' + this.search ).then( ( { data } ) => this.results = data.data );
		    }
		}
	}
</script >