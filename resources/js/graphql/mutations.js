import gql from 'graphql-tag';

export const CREATE_CONTENT = gql`
    mutation CreateContent($content_type_id: ID!, $data: String!) {
        createContent(content_type_id: $content_type_id, data: $data) {
            success
            message
            content {
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
                        rules
                        messages
                    }
                }
                data
            }
        }
    }
`;

export const UPDATE_CONTENT = gql`
    mutation UpdateContent($id: ID!, $data: String!) {
        updateContent(id: $id, data: $data) {
            success
            message
            content {
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
                        rules
                        messages
                    }
                }
                data
            }
        }
    }
`;
