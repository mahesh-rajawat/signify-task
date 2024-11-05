Module ProductCustomAttributeGraphQL

## Main Functionalities
  -  Add additional_type Product attribute and make it available on graphQl endpoint.
  - 
## Attributes

 - Product - Additional Type (additional_type)

### GraphQL
  - Request:
```
query products($search: String, $filter: ProductAttributeFilterInput, $pageSize: Int, $currentPage: Int, $sort: ProductAttributeSortInput) {
  products(
    search: $search
    filter: $filter
    pageSize: $pageSize
    currentPage: $currentPage
    sort: $sort
  ) {
    items {
      sku
      additional_type
    }
    total_count
  }
}
```
 - Response:
```
{
  "data": {
    "products": {
      "items": [
        {
          "sku": "840006659327",
          "additional_type": "Exclusive"
        },
        {
          "sku": "7332150609981",
          "additional_type": "Exclusive"
        },
        {
          "sku": "4211125464141",
          "additional_type": "Exclusive"
        }
      ],
      "total_count": 3
    }
  }
}
```
