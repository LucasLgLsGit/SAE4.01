INSERT INTO Evenement (titre_event, date_debut, date_fin, adresse, description, prix, id_user) VALUES
('Conférence Tech 2025', '2025-06-15 09:00:00', '2025-06-15 18:00:00', 'Paris, La Défense', 'Une conférence sur les nouvelles technologies.', 49.99, 2),
('Hackathon 48H', '2025-07-20 10:00:00', '2025-07-22 10:00:00', 'Lyon, H4ckLab', 'Un hackathon de 48h avec des défis innovants.', 0.00, 2),
('Soirée Networking', '2025-05-10 19:00:00', NULL, 'Marseille, Rooftop Club', 'Rencontrez des professionnels du secteur IT.', 25.00, 2),
('Formation SQL Avancée', '2025-08-05 14:00:00', '2025-08-05 17:00:00', 'Lille, Espace Formation', 'Approfondissement des requêtes SQL et optimisation.', 75.00, 2),
('Meetup Développeurs', '2025-06-25 18:30:00', NULL, 'Bordeaux, Café Tech', 'Une rencontre conviviale entre développeurs.', 10.00, 2),
('Atelier IA & Machine Learning', '2025-09-10 09:30:00', '2025-09-10 16:00:00', 'Toulouse, TechLab', 'Un atelier pratique pour découvrir l’IA.', 20.00, 2),
('Séminaire Cybersécurité', '2025-10-15 08:00:00', '2025-10-15 17:00:00', 'Nantes, Palais des Congrès', 'Présentation des enjeux et bonnes pratiques en cybersécurité.', 85.00, 2),
('Game Jam 2025', '2025-11-01 12:00:00', '2025-11-03 12:00:00', 'Montpellier, GameSpace', 'Concours de création de jeux vidéo en équipe.', 0.00, 2),
('Web Summit', '2025-12-05 09:00:00', '2025-12-07 18:00:00', 'Nice, Palais des Expos', 'Le plus grand salon dédié au web et à l’innovation.', 50.00, 2),
('Formation React.js', '2025-06-10 13:00:00', '2025-06-10 17:00:00', 'Strasbourg, Dev Academy', 'Maîtrisez React.js en une journée avec un expert.', 90.00, 2),
('Sommet de l’Innovation 2026', '2026-03-15 09:00:00', '2026-03-17 18:00:00', 'Lyon, Centre des Congrès', 'Un événement international dédié aux dernières innovations technologiques.', 20.00, 3);

INSERT INTO Question (question, reponse) VALUES
('Quelle est la mission du BDE ?', 'La mission du BDE est d''organiser des événements pour les étudiants et de favoriser la cohésion entre les promotions.'),
('Comment puis-je participer aux événements ?', 'Vous pouvez participer aux événements en vous inscrivant via notre site ou en contactant directement le BDE.'),
('Quels sont les avantages d''être adhérent au BDE ?', 'Être adhérent au BDE vous permet de bénéficier de réductions sur les événements et d''accéder à des ressources exclusives.'),
('Comment puis-je devenir membre du BDE ?', 'Pour devenir membre du BDE, il vous suffit de vous inscrire en ligne via notre formulaire d''adhésion ou lors de nos événements.'),
('Quels types d''événements sont organisés par le BDE ?', 'Le BDE organise une variété d''événements, allant des soirées étudiantes, des conférences, des sorties culturelles, aux tournois sportifs et des activités de détente.'),
('Quand sont organisées les élections du BDE ?', 'Les élections du BDE ont lieu chaque année à la fin du semestre d''automne, généralement en décembre.'),
('Puis-je proposer un événement au BDE ?', 'Oui, nous encourageons les propositions d''événements ! Vous pouvez nous soumettre vos idées via notre formulaire en ligne ou en contactant directement les membres du BDE.'),
('Quels sont les critères pour rejoindre un comité du BDE ?', 'Pour rejoindre un comité du BDE, il faut être adhérent et démontrer un intérêt pour l''organisation d''événements. Il vous suffit de postuler lors de l''appel à candidatures, généralement en début d''année universitaire.'),
('Comment puis-je contacter le BDE ?', 'Vous pouvez contacter le BDE via notre site web, par e-mail ou directement dans nos locaux.'),
('Le BDE propose-t-il des partenariats avec des entreprises ?', 'Oui, le BDE travaille avec des entreprises partenaires pour proposer des offres spéciales aux étudiants et organiser des événements en collaboration.'),
('Y a-t-il des frais d''adhésion au BDE ?', 'Oui, l''adhésion au BDE est soumise à une cotisation annuelle qui permet de financer les activités et événements organisés.'),
('Comment puis-je être informé des événements à venir ?', 'Toutes les annonces sont publiées sur notre site, notre newsletter et nos réseaux sociaux.'),
('Le BDE propose-t-il des services aux étudiants ?', 'Oui, en plus des événements, le BDE met en place divers services comme des réductions, des ressources académiques et du soutien aux nouveaux étudiants.');

INSERT INTO Actualite (titre_article, contenu, date_publication, id_user) VALUES
('L’IA dépasse l’humain dans les échecs en 3 coups', 
 'Une nouvelle intelligence artificielle a battu le champion du monde en un temps record.', 
 '2026-03-12 09:00:00', 2),

('La 6G déployée dans plusieurs pays', 
 'Les premiers tests de la 6G montrent des vitesses 100 fois supérieures à la 5G.', 
 '2026-05-22 14:30:00', 2),

('Mars : premiers tests d’une serre automatisée', 
 'Les scientifiques ont commencé la culture de légumes sur la planète rouge.', 
 '2026-07-19 11:45:00', 2),

('Le premier jeu vidéo contrôlé par la pensée', 
 'Une startup révolutionne l’industrie avec un jeu qui se joue uniquement par l’activité cérébrale.', 
 '2026-08-05 18:10:00', 2),

('Un film entièrement généré par une IA sort au cinéma', 
 'L’industrie du cinéma entre dans une nouvelle ère avec un long-métrage réalisé sans intervention humaine.', 
 '2026-10-29 20:00:00', 2),

('Des drones-livreurs en service dans toutes les grandes villes', 
 'La livraison par drones devient la norme dans les centres urbains.', 
 '2026-12-12 15:30:00', 2);

