package gr.ntua.ivml.mint.persistent;

public interface Lockable {
	public Long getDbID();
	public String getLockname();
}
