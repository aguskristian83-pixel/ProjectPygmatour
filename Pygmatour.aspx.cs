using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text;

namespace PygmaPortfolio
{
    public partial class _Default : System.Web.UI.Page
    {
        [Serializable]
        private class CommentEntry
        {
            public int Id { get; set; }
            public string Name { get; set; }
            public string Email { get; set; }
            public string Website { get; set; }
            public string Message { get; set; }
            public DateTime CreatedAt { get; set; }
            public int? ParentId { get; set; }
        }

        protected void Page_Load(object sender, EventArgs e)
        {
            if (Session["Comments"] == null)
            {
                Session["Comments"] = new List<CommentEntry>();
            }

            RenderComments();
            RenderReplyBanner();
        }

        protected void btnSubmit_Click(object sender, EventArgs e)
        {
            string name = txtName.Text.Trim();
            string email = txtEmail.Text.Trim();
            string website = txtWebsite.Text.Trim();
            string message = txtMessage.Text.Trim();

            if (string.IsNullOrWhiteSpace(name) || string.IsNullOrWhiteSpace(email) || string.IsNullOrWhiteSpace(message))
            {
                lblStatus.CssClass = "text-danger mt-3 d-block";
                lblStatus.Text = "Please fill in your name, email, and comment.";
                return;
            }

            var comments = Session["Comments"] as List<CommentEntry> ?? new List<CommentEntry>();
            int replyId;
            int.TryParse(Request.QueryString["reply"], out replyId);

            comments.Insert(0, new CommentEntry
            {
                Id = comments.Count == 0 ? 1 : comments.Max(c => c.Id) + 1,
                Name = name,
                Email = email,
                Website = website,
                Message = message,
                CreatedAt = DateTime.Now,
                ParentId = replyId > 0 ? (int?)replyId : null
            });

            Session["Comments"] = comments;

            lblStatus.CssClass = "text-success mt-3 d-block";
            lblStatus.Text = "Thank you, " + name + "! Your message has been sent successfully.";

            txtName.Text = "";
            txtEmail.Text = "";
            txtWebsite.Text = "";
            txtMessage.Text = "";

            RenderComments();
        }

        private void RenderComments()
        {
            var comments = Session["Comments"] as List<CommentEntry> ?? new List<CommentEntry>();
            int totalComments = comments.Count;

            litCommentCount.Text = totalComments + " thought" + (totalComments == 1 ? "" : "s") + " on \"Home\"";

            if (totalComments == 0)
            {
                litComments.Text = "";
                pnlRecentComments.Visible = true;
                return;
            }

            var sb = new StringBuilder();
            foreach (var comment in comments.Where(c => c.ParentId == null).OrderByDescending(c => c.CreatedAt))
            {
                sb.Append(BuildCommentHtml(comment));
                var replies = comments.Where(c => c.ParentId == comment.Id).OrderBy(c => c.CreatedAt);
                foreach (var reply in replies)
                {
                    sb.Append(BuildCommentHtml(reply, true));
                }
            }

            litComments.Text = sb.ToString();
            pnlRecentComments.Visible = true;
        }

        private void RenderReplyBanner()
        {
            var comments = Session["Comments"] as List<CommentEntry> ?? new List<CommentEntry>();
            int replyId;
            int.TryParse(Request.QueryString["reply"], out replyId);

            if (replyId <= 0)
            {
                litReplyTo.Text = "";
                litCancelReply.Text = "";
                return;
            }

            var target = comments.FirstOrDefault(c => c.Id == replyId);
            if (target == null)
            {
                litReplyTo.Text = "";
                litCancelReply.Text = "";
                return;
            }

            litReplyTo.Text = "<label class='reply-to-label'>Reply to " + Server.HtmlEncode(target.Name) + "</label>";
            litCancelReply.Text = "<a href='Pygmatour.aspx' class='cancel-reply'>Cancel reply</a>";
        }

        private string BuildCommentHtml(CommentEntry comment, bool isReply = false)
        {
            var author = Server.HtmlEncode(comment.Name);
            var date = comment.CreatedAt.ToString("MMMM dd, yyyy 'at' h:mm tt", CultureInfo.InvariantCulture);
            var initial = string.IsNullOrEmpty(comment.Name) ? "?" : comment.Name[0].ToString().ToUpperInvariant();
            var commentText = Server.HtmlEncode(comment.Message).Replace(Environment.NewLine, "<br />");
            var replyLink = "<a href='Pygmatour.aspx?reply=" + comment.Id + "' class='comment-reply-link'>Reply</a>";

            var nestingClass = isReply ? " comment-item-reply" : "";

            return string.Format(
                "<div class='comment-item{0}'>" +
                "<div class='comment-avatar'>{1}</div>" +
                "<div class='comment-content'>" +
                "<div class='comment-meta'>" +
                "<div class='comment-author'>{2}</div>" +
                "<div class='comment-date'>{3}</div>" +
                "</div>" +
                "{4}" +
                "<div class='comment-body'>{5}</div>" +
                "</div>" +
                "</div>",
                nestingClass,
                initial,
                author,
                date,
                replyLink,
                commentText);
        }
    }
}
