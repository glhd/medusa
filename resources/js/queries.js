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
            }
            rules
            messages
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
                }
                rules
                messages
            }
            data
        }
    }
`;

export const CREATE_CONTENT = gql`
    mutation CreateContent($content_type_id: ID!, $data: String!) {
        createContent(
            content: {
                content_type_id: $content_type_id,
                data: $data
            }
        ) {
            id
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
                }
                rules
                messages
            }
            data
        }
    }
`;

export const UPDATE_CONTENT = gql`
    mutation UpdateContent($id: ID!, $data: String!) {
        updateContent(id: $id, data: $data) {
            id
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
                }
                rules
                messages
            }
            data
        }
    }
`;
