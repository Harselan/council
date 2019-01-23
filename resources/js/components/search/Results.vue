<template>
	<table style="border-collapse: collapse">
		<tbody v-if="results.length">
			<result v-for="result in results" :data="result"></result>
		</tbody>
		<tbody v-else>
			<tr>
				<td>Nothing here.</td>
			</tr>
		</tbody>
	</table>
</template>

<script >
	import Result from './Result.vue';

    export default
    {
        props: [ 'results' ],
	    components: { Result }
    }
</script >