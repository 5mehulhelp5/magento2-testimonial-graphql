# Magento 2 Testimonial Module with Enhanced GraphQL APIs

This repository contains a **Magento 2 Testimonial** that demonstrates how to implement **GraphQL** in Magento 2.

## Key Features

- Complete database schema with indexes
- Full admin CRUD with UI components
- Frontend listing and submission pages
- GraphQL endpoints (queries + mutation)
- Proper validation and security
- Luma theme compatible styling
- Repository pattern and service classes
- Comprehensive documentation

## Requirements

- Magento Open Source or Adobe Commerce **2.4.5+**
- PHP **8.1+**

## Module Information

- **Module Name:** `Ashok_Testimonial`
- **Module Type:** Magento 2 Custom Module
- **API Type:** GraphQL

## Installation

1. Copy the module to Magento:

```
app/code/Ashok/Testimonial
```

2. Run Magento commands:

```bash
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
php bin/magento setup:static-content:deploy
```

## GraphQL Endpoint
After installation, the GraphQL endpoint is available at:
```
https://your-magento-site.com/graphql
```

## GraphQL Schema Example
File: `etc/schema.graphqls`

## Queries

### 1. Get All Testimonials (with Filtering & Pagination)

#### Basic Query
```graphql
query {
  testimonials(currentPage: 1, pageSize: 10) {
    total_count
    items {
      testimonial_id
      customer_name
      customer_email
      message
      rating
      status
      created_at
      updated_at
    }
  }
}
```

#### With Filters - Filter by Status
```graphql
query {
  testimonials(
    currentPage: 1
    pageSize: 10
    filter: {
      status: { eq: "1" }
    }
  ) {
    total_count
    items {
      testimonial_id
      customer_name
      message
      rating
      created_at
    }
  }
}
```

#### With Filters - Filter by Rating
```graphql
query {
  testimonials(
    filter: {
      rating: { eq: "5" }
    }
  ) {
    total_count
    items {
      testimonial_id
      customer_name
      message
      rating
    }
  }
}
```

#### With Filters - Search by Name (LIKE)
```graphql
query {
  testimonials(
    filter: {
      customer_name: { like: "John" }
    }
  ) {
    total_count
    items {
      testimonial_id
      customer_name
      message
    }
  }
}
```

#### With Filters - Multiple Ratings (IN)
```graphql
query {
  testimonials(
    filter: {
      rating: { in: ["4", "5"] }
    }
  ) {
    total_count
    items {
      testimonial_id
      customer_name
      rating
    }
  }
}
```

#### With Filters - Date Range
```graphql
query {
  testimonials(
    filter: {
      created_at: {
        from: "2026-01-01"
        to: "2026-01-31"
      }
    }
  ) {
    total_count
    items {
      testimonial_id
      customer_name
      created_at
    }
  }
}
```

#### With Filters - Combined Filters
```graphql
query {
  testimonials(
    currentPage: 1
    pageSize: 20
    filter: {
      status: { eq: "1" }
      rating: { in: ["4", "5"] }
      customer_name: { like: "John" }
    }
  ) {
    total_count
    items {
      testimonial_id
      customer_name
      message
      rating
      status
      created_at
    }
  }
}
```

---

### 2. Get Testimonial by ID

```graphql
query {
  testimonial(id: 5) {
    testimonial_id
    customer_name
    customer_email
    message
    rating
    status
    created_at
    updated_at
  }
}
```

#### Response
```json
{
  "data": {
    "testimonial": {
      "testimonial_id": 5,
      "customer_name": "John Doe",
      "customer_email": "john@example.com",
      "message": "Excellent service!",
      "rating": 5,
      "status": 1,
      "created_at": "2026-01-06 15:00:00",
      "updated_at": "2026-01-06 16:30:00"
    }
  }
}
```

---

## Mutations

### 3. Add Testimonial

```graphql
mutation {
  addTestimonial(
    input: {
      customer_name: "John Doe"
      customer_email: "john@example.com"
      message: "Excellent service! Highly recommended."
      rating: 5
    }
  ) {
    testimonial_id
    status
    message
  }
}
```

#### Response
```json
{
  "data": {
    "addTestimonial": {
      "testimonial_id": 6,
      "status": "success",
      "message": "Testimonial submitted successfully. It will be reviewed by our team."
    }
  }
}
```

---

### 4. Update Testimonial

Update one or more fields of an existing testimonial.

#### Update All Fields
```graphql
mutation {
  updateTestimonial(
    id: 5
    input: {
      customer_name: "John Smith"
      customer_email: "john.smith@example.com"
      message: "Updated message - Still excellent!"
      rating: 5
      status: 1
    }
  ) {
    testimonial_id
    status
    message
    testimonial {
      testimonial_id
      customer_name
      customer_email
      message
      rating
      status
      updated_at
    }
  }
}
```

#### Update Specific Fields Only
```graphql
mutation {
  updateTestimonial(
    id: 5
    input: {
      status: 1
    }
  ) {
    testimonial_id
    status
    message
    testimonial {
      testimonial_id
      status
      updated_at
    }
  }
}
```

#### Update Rating and Message
```graphql
mutation {
  updateTestimonial(
    id: 5
    input: {
      message: "Updated testimonial message"
      rating: 4
    }
  ) {
    testimonial_id
    status
    message
  }
}
```

#### Response
```json
{
  "data": {
    "updateTestimonial": {
      "testimonial_id": 5,
      "status": "success",
      "message": "Testimonial updated successfully.",
      "testimonial": {
        "testimonial_id": 5,
        "customer_name": "John Smith",
        "customer_email": "john.smith@example.com",
        "message": "Updated message - Still excellent!",
        "rating": 5,
        "status": 1,
        "updated_at": "2026-01-06 16:50:00"
      }
    }
  }
}
```

---

### 5. Delete Testimonial

```graphql
mutation {
  deleteTestimonial(id: 5) {
    status
    message
  }
}
```

#### Response
```json
{
  "data": {
    "deleteTestimonial": {
      "status": "success",
      "message": "Testimonial deleted successfully."
    }
  }
}
```

#### Error Response (Not Found)
```json
{
  "errors": [
    {
      "message": "Testimonial with ID 999 not found.",
      "extensions": {
        "category": "graphql-no-such-entity"
      }
    }
  ]
}
```

---

## Filter Options

### Available Filter Fields
- `customer_name` - Filter by customer name
- `customer_email` - Filter by email
- `rating` - Filter by rating (1-5)
- `status` - Filter by status (0=Disabled, 1=Enabled)
- `created_at` - Filter by creation date

### Filter Types

| Filter Type | Description | Example |
|-------------|-------------|---------|
| `eq` | Equal to | `{ eq: "1" }` |
| `in` | In array | `{ in: ["4", "5"] }` |
| `like` | Pattern match | `{ like: "John" }` |
| `from` | Greater than or equal | `{ from: "2026-01-01" }` |
| `to` | Less than or equal | `{ to: "2026-01-31" }` |

---

## cURL Examples

### Get Filtered Testimonials
```bash
curl -X POST https://your-magento-site.com/graphql \
  -H "Content-Type: application/json" \
  -d '{
    "query": "query { testimonials(filter: { status: { eq: \"1\" }, rating: { in: [\"4\", \"5\"] } }) { total_count items { testimonial_id customer_name rating } } }"
  }'
```

### Update Testimonial
```bash
curl -X POST https://your-magento-site.com/graphql \
  -H "Content-Type: application/json" \
  -d '{
    "query": "mutation { updateTestimonial(id: 5, input: { status: 1 }) { testimonial_id status message } }"
  }'
```

### Delete Testimonial
```bash
curl -X POST https://your-magento-site.com/graphql \
  -H "Content-Type: application/json" \
  -d '{
    "query": "mutation { deleteTestimonial(id: 5) { status message } }"
  }'
```

---

## Common Use Cases

### 1. Get Only 5-Star Testimonials
```graphql
query {
  testimonials(filter: { rating: { eq: "5" }, status: { eq: "1" } }) {
    items {
      customer_name
      message
      created_at
    }
  }
}
```

### 2. Get Recent Testimonials (Last 30 Days)
```graphql
query {
  testimonials(
    filter: {
      created_at: { from: "2025-12-07" }
      status: { eq: "1" }
    }
  ) {
    items {
      customer_name
      message
      rating
      created_at
    }
  }
}
```

### 3. Search Testimonials by Customer Email
```graphql
query {
  testimonials(filter: { customer_email: { like: "@example.com" } }) {
    items {
      testimonial_id
      customer_name
      customer_email
      message
    }
  }
}
```

### 4. Approve Testimonial (Change Status)
```graphql
mutation {
  updateTestimonial(id: 10, input: { status: 1 }) {
    status
    message
    testimonial {
      testimonial_id
      status
    }
  }
}
```

### 5. Bulk Approve by Getting Disabled and Updating
```graphql
# Step 1: Get disabled testimonials
query {
  testimonials(filter: { status: { eq: "0" } }) {
    items {
      testimonial_id
      customer_name
      message
    }
  }
}

# Step 2: Update each one
mutation {
  updateTestimonial(id: 10, input: { status: 1 }) {
    status
    message
  }
}
```

---

## Validation Rules

### Add/Update Testimonial
- **customer_name**: Required (for add), max 255 characters
- **customer_email**: Required (for add), valid email format, max 255 characters
- **message**: Required (for add), no length limit
- **rating**: Required (for add), integer 1-5
- **status**: Optional, 0 or 1

### Filter Values
- All filter values should be strings (even numbers)
- Date format: `YYYY-MM-DD` or `YYYY-MM-DD HH:MM:SS`
- Use `like` for partial matches (automatically adds % wildcards)

---

## Status Values
- `0` = Disabled (default for customer submissions)
- `1` = Enabled (visible in public queries)

---

## Complete API Summary

| Operation | Type | Description |
|-----------|------|-------------|
| `testimonials` | Query | Get paginated list with filtering |
| `testimonial` | Query | Get single testimonial by ID |
| `addTestimonial` | Mutation | Create new testimonial |
| `updateTestimonial` | Mutation | Update existing testimonial |
| `deleteTestimonial` | Mutation | Delete testimonial |

---

## 🚀 Testing with GraphQL Playground

1. Navigate to: `https://your-magento-site.com/graphql`
2. Use the schema explorer to see all available fields
3. Test queries and mutations interactively
4. View auto-complete suggestions

---

## 📚 Additional Resources

- **Admin Panel**: `/admin/testimonial/testimonial/index`
- **Frontend Listing**: `/testimonial`
- **Submit Form**: `/testimonial/submit`
- **Module Location**: `/app/code/Ashok/Testimonial`