create table infusemedia.logs
(
    id         int auto_increment primary key,
    ip_address varchar(255) not null,
    user_agent varchar(255) not null,
    view_date  datetime     not null,
    image_id   int          not null,
    view_count int          not null
);

