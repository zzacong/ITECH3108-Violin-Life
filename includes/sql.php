
<?php

$top3_violins_sql = "
  SELECT 
    of.violin_id, 
    vi.title, 
    COUNT(*) AS no_of_offer 
  FROM 
    offer of
  JOIN 
    violin vi 
  ON 
    of.violin_id = vi.id
  GROUP BY of.violin_id, vi.title
  ORDER BY no_of_offer DESC
  LIMIT 3
  ";

$sign_up_sql = "
  INSERT INTO `user` (
    name, username, email, password, location
  ) VALUES (
    :name, :username, :email, :hashed_password, :location
  )
  ";

$view_offers_sql = "
  SELECT 
    u.name AS owner_name, 
    u.username AS owner_username, 
    v.id AS violin_id, 
    v.title, 
    v.seeking, 
    v.description, 
    v.submitted, 
    o.id AS offer_id, 
    o.offer, 
    o.accepted, 
    o.submitted AS offered, 
    ou.name AS offerer_name, 
    ou.username AS offerer_username    
  FROM 
    `user` u
  JOIN
    violin v
  ON 
    u.id = v.user_id
  JOIN
    offer o
  ON 
    v.id = o.violin_id
  JOIN
    `user` ou
  ON 
    o.user_id = ou.id
  ORDER BY
    v.submitted DESC, o.submitted DESC
  ";

$make_offer_sql = "
  INSERT INTO offer (
    user_id, 
    violin_id, 
    offer
  )
  VALUES (
    :user_id, :violin_id, :offer
  )
  ";

$get_owner_sql = "
SELECT 
  u.username 
FROM 
  `user` u 
JOIN 
  violin v 
ON 
  u.id = v.user_id 
WHERE 
  v.id = :violin_id
";

$violin_details_sql = "
  SELECT 
    title, description, seeking 
  FROM 
    violin 
  WHERE 
    id = :violin_id
";

$get_exchanges_sql = "
  SELECT
    o.id AS offer_id,
    o.offer,
    o.accepted,
    uof.name AS offerer_name,
    uof.username AS offerer_username,
    v.title AS violin_title,
    uow.id AS owner_id,
    uow.name AS owner_name,
    uow.username AS owner_username
  FROM
    offer o
  JOIN
    user uof
    ON
      o.user_id = uof.id
  JOIN
    violin v
    ON
      o.violin_id = v.id
  JOIN
    user uow
    ON
      v.user_id = uow.id
  WHERE
      o.accepted IS NOT NULL
    AND (
        uow.username = :current_user
      OR
        uof.username = :current_user
    )
  ORDER BY
    o.accepted ASC
";

$get_messages_sql = "
  SELECT
    m.offer_id,
    m.text,
    m.sent,
    uf.name AS from_name,
    uf.username AS from_username,
    ut.name AS to_name,
    ut.username AS to_username
  FROM
    message m
  JOIN
    user uf
    ON
      m.from_user_id = uf.id
  JOIN
    user ut
    ON
      m.to_user_id = ut.id
  WHERE
      m.offer_id = :offer_id
  ORDER BY
    m.sent ASC
";

$send_message_sql = "
  INSERT INTO message (
    from_user_id, to_user_id, offer_id, text
  )
  VALUES (
    :from_user, :to_user, :offer_id, :text
  )
";

$get_user_id_sql = "
  SELECT 
    id 
  FROM 
    `user` 
  WHERE 
    username = :username
";

?>