import gql from 'graphql-tag';

export const ALL_CONTENT_TYPES = gql`
    {
        allContentTypes {
            id
            title
            is_singleton
            fields {
                name
                component
                display_name
                label
                config
                initial_value
                rules
                messages
            }
        }
    }
`;

export const GET_CONTENT_TYPE = gql`
    query GetContentType($id: ID!) {
        getContentType(id: $id) {
            id
            title
            is_singleton
            fields {
                name
                component
                display_name
                label
                config
                initial_value
                rules
                messages
            }
        }
    }
`;

export const ALL_CONTENT = gql`
    query AllContent($page: Int) {
        allContent(page: $page) {
            total
            per_page
            content {
                id
                slug
                description
                content_type {
                    title
                }
            }
        }
    }
`;

export const GET_CONTENT = gql`
    query GetContent($id: ID!) {
        getContent(id: $id) {
            id
            slug
            description
            content_type {
                id
                title
                is_singleton
                fields {
                    name
                    component
                    display_name
                    label
                    config
                    initial_value
                    rules
                    messages
                }
            }
            data
        }
    }
`;

export const SEARCH_CONTENT = gql`
    query SearchContent($query: String!, $content_type_id: ID, $page: Int) {
        searchContent(query: $query, content_type_id: $content_type_id, page: $page) {
            total
            per_page
            content {
                id
                slug
                description
                content_type {
                    id
                    title
                }
            }
        }
    }
`;
