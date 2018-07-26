<!--
 *  Copyright (c) 2015, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the license found in the
 *  LICENSE file in the root directory of this source tree.
 *
-->
<!DOCTYPE html>
<html>
  <head>
    <style>
      body {
        height: 100%;
        margin: 0;
        width: 100%;
        overflow: hidden;
      }
      #graphiql {
        height: 100vh;
      }

    </style>
    <link rel="stylesheet" href="{{config('graphiql.paths.assets_public')}}/graphiql.css" />
    <!-- <script src="//cdn.jsdelivr.net/fetch/0.9.0/fetch.min.js"></script> -->
    <script>window.fetch || document.write('<script src="{{config('graphiql.paths.assets_public')}}/vendor/fetch.min.js">\x3C/script>')</script>
    <!-- <script src="//cdn.jsdelivr.net/react/15.0.1/react.min.js"></script> -->
    <script>window.React || document.write('<script src="{{config('graphiql.paths.assets_public')}}/vendor/react-15.4.2.min.js">\x3C/script>')</script>
    <!-- <script src="//cdn.jsdelivr.net/react/15.0.1/react&#45;dom.min.js"></script> -->
    <script>window.ReactDOM || document.write('<script src="{{config('graphiql.paths.assets_public')}}/vendor/react-dom-15.4.2.min.js">\x3C/script>')</script>
    <script src="{{config('graphiql.paths.assets_public')}}/graphiql.min.js"></script>
  </head>
  <body>
    <div id="graphiql">Loading...</div>
    <script>

      /**
       * This GraphiQL example illustrates how to use some of GraphiQL's props
       * in order to enable reading and updating the URL parameters, making
       * link sharing of queries a little bit easier.
       *
       * This is only one example of this kind of feature, GraphiQL exposes
       * various React params to enable interesting integrations.
       */

      // Parse the search string to get url parameters.
      var search = window.location.search;
      var parameters = {};
      search.substr(1).split('&').forEach(function (entry) {
        var eq = entry.indexOf('=');
        if (eq >= 0) {
          parameters[decodeURIComponent(entry.slice(0, eq))] =
            decodeURIComponent(entry.slice(eq + 1));
        }
      });

      // if variables was provided, try to format it.
      if (parameters.variables) {
        try {
          parameters.variables =
            JSON.stringify(JSON.parse(parameters.variables), null, 2);
        } catch (e) {
          // Do nothing, we want to display the invalid JSON as a string, rather
          // than present an error.
        }
      }

      // When the query and variables string is edited, update the URL bar so
      // that it can be easily shared
      function onEditQuery(newQuery) {
        parameters.query = newQuery;
        updateURL();
      }

      function onEditVariables(newVariables) {
        parameters.variables = newVariables;
        updateURL();
      }

      function onEditOperationName(newOperationName) {
        parameters.operationName = newOperationName;
        updateURL();
      }

      function updateURL() {
        var newSearch = '?' + Object.keys(parameters).filter(function (key) {
          return Boolean(parameters[key]);
        }).map(function (key) {
          return encodeURIComponent(key) + '=' +
            encodeURIComponent(parameters[key]);
        }).join('&');
        history.replaceState(null, null, newSearch);
      }

      // Defines a GraphQL fetcher using the fetch API.
      function graphQLFetcher(graphQLParams) {
        //return fetch("http://localhost:8000/graphql", {
        return fetch("{{url(config('graphiql.routes.graphql'))}}", {
          method: 'post',
          headers: {
            @forelse(config('graphiql.headers') as $key => $value)
              '{{ $key }}': '{{ $value }}',
            @empty
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            @endforelse
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
          },
          body: JSON.stringify(graphQLParams),
          credentials: 'same-origin',
        }).then(function (response) {
          return response.text();
        }).then(function (responseBody) {
          try {
            return JSON.parse(responseBody);
          } catch (error) {
            return responseBody;
          }
        });
      }

      var defaultQuery = `# Welkom bij GraphiQL voor de KoornBase.
#
# Op deze pagina kun je gemakkelijk GraphQL queries schrijven en testen voor je apps.
# De resultaten komen direct uit de database.
#
# LET OP! Je gebruikt de huidige gegevens in de database, dus pas op met mutations!
#
# Alle queries worden uitgevoerd namens de 'huidige' gebruiker. Alleen de gegevens
# die deze gebruiker mag zien, worden getoond. Evenzo kunnen alleen de mutations
# waarvoor de gebruiker rechten heeft worden uitgevoerd.
#
#
#
#
# Toetscombinaties:
#
#  Prettify Query:  Shift-Ctrl-P (of druk op de 'Prettify' knop hierboven.)
#
#       Run Query:  Ctrl-Enter (of druk op de Play knop hierboven.)
#
#   Auto Complete:  Ctrl-Space (of begin gewoon met typen.)
#
`;

      // Render <GraphiQL /> into the body.
      ReactDOM.render(
        React.createElement(GraphiQL, {
          fetcher: graphQLFetcher,
          query: parameters.query || defaultQuery,
          variables: parameters.variables,
          operationName: parameters.operationName,
          onEditQuery: onEditQuery,
          onEditVariables: onEditVariables,
          onEditOperationName: onEditOperationName,
        }, [
            React.createElement(GraphiQL.Logo, {}, [
                React.createElement('a', {
                    href:'/',
                    style: {
                        'color':'#868e96',
                        'text-decoration':'none'
                    }
                }, [
                    React.createElement('img', {
                        src:'/images/koornbase/cloud-eye.svg',
                        style: {
                            height:'30px',
                            'vertical-align':'bottom',
                            'margin-right':'6px',
                            'margin-top':'-3px',
                            'margin-bottom':'-3px'
                        }
                    }),
                    'KoornBase'
                ]),
                React.createElement('span', {}, [
                    ' Graph',
                    React.createElement('em', {}, 'i'),
                    'QL'
                ])
            ]),
        ]),
        document.getElementById('graphiql')
      );
    </script>
  </body>
</html>
