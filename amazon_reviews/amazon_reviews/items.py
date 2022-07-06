# Define here the models for your scraped items
#
# See documentation in:
# https://docs.scrapy.org/en/latest/topics/items.html

import scrapy


class AmazonReviewsItem(scrapy.Item):
    # define the fields for your item here like:
    ratings = scrapy.Field()
    review_title = scrapy.Field()
    reviews = scrapy.Field()
    helpful = scrapy.Field()
    country = scrapy.Field()
    date = scrapy.Field()
    image = scrapy.Field()
    pass
